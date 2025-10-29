<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ClientAppointmentController extends Controller
{
    /**
     * Lista las citas del cliente autenticado.
     */
    public function index()
    {
        $user = Auth::user();

        $appointments = Appointment::with(['patient', 'doctor', 'consultation'])
            ->whereHas('patient', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('owner_user_id', $user->id);
            })
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        return view('cliente.appointments.index', compact('appointments'));
    }

    /**
     * Formulario para crear una cita.
     */
    public function create()
    {
        $dependents = Patient::where('owner_user_id', Auth::id())->orderBy('name')->get();
        return view('cliente.appointments.create', compact('dependents'));
    }

    /**
     * Guarda la cita validando que el slot siga disponible.
     */
    public function store(Request $request)
    {
        $request->validate([
            'for'       => 'required|in:me,dependent_existing,dependent_new',
            'date'      => 'required|date|after_or_equal:today',
            'time'      => 'required',
            'doctor_id' => 'required|exists:users,id',
            'notes'     => 'nullable|string|max:500',

            'patient_id'    => 'required_if:for,dependent_existing|nullable|exists:patients,id',
            'dep_name'      => 'required_if:for,dependent_new|nullable|string|max:255',
            'dep_lastname'  => 'nullable|string|max:255',
            'dep_birthdate' => 'required_if:for,dependent_new|nullable|date',
            'dep_dpi'       => 'nullable|string|max:50|unique:patients,dpi',
            'dep_phone'     => 'nullable|string|max:50',
            'dep_email'     => 'nullable|email|max:255',
        ], [
            'patient_id.required_if'    => 'Debes seleccionar un dependiente.',
            'dep_name.required_if'      => 'El nombre del dependiente es obligatorio.',
            'dep_birthdate.required_if' => 'La fecha de nacimiento del dependiente es obligatoria.',
        ]);

        $user = Auth::user();
        $for  = $request->input('for');

        // 1) Resolver paciente
        if ($for === 'me') {
            $patient = Patient::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'owner_user_id' => $user->id,
                    'name'          => $user->name,
                    'lastname'      => '',
                    'email'         => $user->email,
                    'dpi'           => null,
                ]
            );
        } elseif ($for === 'dependent_existing') {
            $patient = Patient::where('id', $request->patient_id)
                ->where('owner_user_id', $user->id)
                ->first();

            if (! $patient) {
                throw ValidationException::withMessages([
                    'patient_id' => 'El dependiente seleccionado no te pertenece.',
                ]);
            }
        } else {
            $patient = Patient::create([
                'user_id'       => null,
                'owner_user_id' => $user->id,
                'dpi'           => $request->dep_dpi,
                'name'          => $request->dep_name,
                'lastname'      => $request->dep_lastname,
                'birthdate'     => $request->dep_birthdate,
                'phone'         => $request->dep_phone,
                'email'         => $request->dep_email,
            ]);
        }

        // Normaliza la hora entrante por seguridad
        $normalizedTime = $this->normalizeTime($request->time);

        // 2) Impedir doble reserva del mismo doctor/día/hora
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where('time', 'like', $normalizedTime . '%') // coincide HH:MM y HH:MM:SS
            ->whereIn('status', ['pendiente', 'confirmada']) // estados que bloquean
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'time' => 'Ese horario ya no está disponible. Elige otro.',
            ]);
        }

        // 3) Crear cita
        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id'  => $request->doctor_id,
            'date'       => $request->date,
            'time'       => $normalizedTime, // guarda en HH:MM para coherencia
            'status'     => 'pendiente',
            'notes'      => $request->notes,
        ]);

        return redirect()
            ->route('cliente.appointments.index')
            ->with('success', '¡Tu cita fue agendada con éxito!');
    }

    /**
     * Endpoint AJAX: horarios disponibles para una fecha (y opcionalmente un médico).
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date'      => 'required|date|after_or_equal:today',
            'doctor_id' => 'nullable|exists:users,id',
        ]);

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek; // 0=Dom ... 6=Sáb

        // 1) Médicos
        if ($request->filled('doctor_id')) {
            $doctorIds = collect([(int) $request->doctor_id]);
        } else {
            $doctorIds = User::role('Médico')->pluck('id');
        }

        if ($doctorIds->isEmpty()) {
            return response()->json(['success' => true, 'date' => $date->toDateString(), 'slots' => []]);
        }

        // 2) Horarios activos para ese día
        $schedules = DoctorSchedule::whereIn('doctor_id', $doctorIds)
            ->where('day_of_week', $dayOfWeek)
            ->where('active', true)
            ->get()
            ->groupBy('doctor_id');

        if ($schedules->isEmpty()) {
            return response()->json(['success' => true, 'date' => $date->toDateString(), 'slots' => []]);
        }

        // 3) Citas ocupadas ese día por médico (normalizadas a H:i)
        $occupiedByDoctor = Appointment::whereIn('doctor_id', $doctorIds)
            ->where('date', $date->toDateString())
            ->whereIn('status', ['pendiente', 'confirmada'])
            ->get()
            ->groupBy('doctor_id')
            ->map(function ($items) {
                $times = $items->pluck('time')->toArray();
                return array_values(array_unique(array_map([$this, 'normalizeTime'], $times)));
            });

        // 4) Nombres de médicos
        $doctorNames = User::whereIn('id', $doctorIds)->pluck('name', 'id');

        // 5) Construir slots filtrando ocupados y pasados
        $result = [];
        foreach ($schedules as $doctorId => $doctorSchedules) {
            $occupiedTimes = $occupiedByDoctor->get($doctorId, []);
            foreach ($doctorSchedules as $sch) {
                $start = Carbon::parse($sch->start_time);
                $end   = Carbon::parse($sch->end_time);
                $step  = $sch->slot_minutes ?: 30;

                while ($start < $end) {
                    $time = $start->format('H:i');

                    // Oculta horas pasadas si la fecha es hoy
                    if ($this->isPastToday($date, $time)) {
                        $start->addMinutes($step);
                        continue;
                    }

                    if (!in_array($time, $occupiedTimes, true)) {
                        $result[] = [
                            'time'        => $time,
                            'doctor_id'   => (int) $doctorId,
                            'doctor_name' => $doctorNames[$doctorId] ?? 'Médico',
                        ];
                    }

                    $start->addMinutes($step);
                }
            }
        }

        // Orden
        usort($result, function ($a, $b) {
            return $a['time'] === $b['time']
                ? strcmp($a['doctor_name'], $b['doctor_name'])
                : strcmp($a['time'], $b['time']);
        });

        return response()->json([
            'success' => true,
            'date'    => $date->toDateString(),
            'slots'   => $result,
        ]);
    }

    /**
     * Lógica central de slots para una fecha (todos los médicos activos).
     */
    private function buildAvailableSlotsForDate(Carbon $date): array
    {
        $dayOfWeek = $date->dayOfWeek;

        $doctorIds = User::role('Médico')->pluck('id');
        if ($doctorIds->isEmpty()) return [];

        $schedules = DoctorSchedule::whereIn('doctor_id', $doctorIds)
            ->where('day_of_week', $dayOfWeek)
            ->where('active', true)
            ->get()
            ->groupBy('doctor_id');
        if ($schedules->isEmpty()) return [];

        // Ocupadas normalizadas
        $occupiedByDoctor = Appointment::whereIn('doctor_id', $doctorIds)
            ->where('date', $date->toDateString())
            ->whereIn('status', ['pendiente', 'confirmada'])
            ->get()
            ->groupBy('doctor_id')
            ->map(function ($items) {
                $times = $items->pluck('time')->toArray();
                return array_values(array_unique(array_map([$this, 'normalizeTime'], $times)));
            });

        // Horas ya reservadas por el usuario y sus dependientes (normalizadas)
        $userPatientIds = Patient::where(function ($q) {
            $q->where('user_id', Auth::id())
                ->orWhere('owner_user_id', Auth::id());
        })->pluck('id');

        $userBookedTimes = Appointment::whereIn('patient_id', $userPatientIds)
            ->where('date', $date->toDateString())
            ->pluck('time')
            ->map([$this, 'normalizeTime'])
            ->unique()
            ->values()
            ->toArray();

        $doctorNames = User::whereIn('id', $doctorIds)->pluck('name', 'id');

        $result = [];
        foreach ($schedules as $doctorId => $doctorSchedules) {
            $occupiedTimes = $occupiedByDoctor->get($doctorId, []);
            foreach ($doctorSchedules as $sch) {
                $start = Carbon::parse($sch->start_time);
                $end   = Carbon::parse($sch->end_time);
                $step  = $sch->slot_minutes ?: 30;

                while ($start < $end) {
                    $time = $start->format('H:i');

                    if (
                        !in_array($time, $occupiedTimes, true) &&
                        !in_array($time, $userBookedTimes, true) &&
                        ! $this->isPastToday($date, $time)
                    ) {
                        $result[] = [
                            'time'        => $time,
                            'doctor_id'   => (int) $doctorId,
                            'doctor_name' => $doctorNames[$doctorId] ?? 'Médico',
                        ];
                    }

                    $start->addMinutes($step);
                }
            }
        }

        usort($result, function ($a, $b) {
            if ($a['time'] === $b['time']) {
                return strcmp($a['doctor_name'], $b['doctor_name']);
            }
            return strcmp($a['time'], $b['time']);
        });

        return $result;
    }

    /**
     * Normaliza 'HH:MM' o 'HH:MM:SS' a 'HH:MM'.
     */
    private function normalizeTime($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->format('H:i');
        }
        $val = (string) $value;
        // Soporta 'HH:MM', 'HH:MM:SS' o 'H:i' procedentes de UI
        return substr($val, 0, 5);
    }

    /**
     * Determina si el tiempo está en el pasado cuando la fecha es hoy.
     * Útil para ocultar horarios pasados del mismo día.
     */
    private function isPastToday(Carbon $date, string $timeHhmm): bool
    {
        if (! $date->isToday()) {
            return false;
        }
        $now = Carbon::now();
        $slot = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $timeHhmm);
        return $slot->lessThanOrEqualTo($now);
    }
}

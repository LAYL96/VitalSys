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
        // Usuario autenticado
        $user = Auth::user(); // evita "auth()" si te está dando conflicto

        // Traer citas cuyos pacientes pertenecen al usuario (él mismo o sus dependientes)
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
     * La vista carga horarios y médicos disponibles vía AJAX según la fecha.
     */
    public function create()
    {
        $dependents = Patient::where('owner_user_id', Auth::id())->orderBy('name')->get();
        return view('cliente.appointments.create', compact('dependents'));
    }

    /**
     * Guarda la cita validando que el slot (doctor/hora) siga disponible.
     */
    public function store(Request $request)
    {
        // Validación base
        $request->validate([
            'for'       => 'required|in:me,dependent_existing,dependent_new',
            'date'      => 'required|date|after_or_equal:today',
            'time'      => 'required',
            'doctor_id' => 'required|exists:users,id',
            'notes'     => 'nullable|string|max:500',

            // Dependiente existente
            'patient_id'    => 'required_if:for,dependent_existing|nullable|exists:patients,id',

            // Nuevo dependiente
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

        // 1) Resolver el paciente ($patient) según la opción elegida
        if ($for === 'me') {
            // Si no existe registro Patient para esta cuenta, créalo “ligero”
            $patient = Patient::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'owner_user_id' => $user->id,      // el mismo usuario es su “propietario”
                    'name'          => $user->name,    // relleno básico
                    'lastname'      => '',
                    'email'         => $user->email,
                    'dpi'           => null,           // opcional
                ]
            );
        } elseif ($for === 'dependent_existing') {
            // Asegurar que el dependiente pertenece al usuario
            $patient = Patient::where('id', $request->patient_id)
                ->where('owner_user_id', $user->id)
                ->first();

            if (!$patient) {
                throw ValidationException::withMessages([
                    'patient_id' => 'El dependiente seleccionado no te pertenece.',
                ]);
            }
        } else { // 'dependent_new'
            $patient = Patient::create([
                'user_id'       => null,           // no tiene cuenta propia
                'owner_user_id' => $user->id,      // el usuario actual es el propietario/gestor
                'dpi'           => $request->dep_dpi,
                'name'          => $request->dep_name,
                'lastname'      => $request->dep_lastname,
                'birthdate'     => $request->dep_birthdate,
                'phone'         => $request->dep_phone,
                'email'         => $request->dep_email,
                // Si tu migración tiene gender/address y quieres guardarlos aquí, agrégalos.
            ]);
        }

        // 2) Impedir doble reserva del mismo doctor/día/hora
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'time' => 'Ese horario ya no está disponible. Elige otro.',
            ]);
        }

        // 3) Crear la cita con el patient_id resuelto
        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id'  => $request->doctor_id,
            'date'       => $request->date,
            'time'       => $request->time,
            'status'     => 'pendiente',
            'notes'      => $request->notes,
        ]);

        return redirect()
            ->route('cliente.appointments.index')
            ->with('success', '¡Tu cita fue agendada con éxito!');
    }




    /**
     * Endpoint AJAX: devuelve horarios + médicos disponibles para una fecha dada.
     * Respuesta:
     * {
     *   success: true,
     *   date: "YYYY-MM-DD",
     *   slots: [{time, doctor_id, doctor_name}, ...],
     *   doctors: [{id, name}, ...]
     * }
     */



    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date'      => 'required|date|after_or_equal:today',
            'doctor_id' => 'nullable|exists:users,id',
        ]);

        $date = \Carbon\Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek; // 0=Dom, ... 6=Sáb

        // 1) Médicos (si llega uno, filtramos; si no, todos con rol "Médico")
        if ($request->filled('doctor_id')) {
            $doctorIds = collect([(int) $request->doctor_id]);
        } else {
            $doctorIds = \App\Models\User::role('Médico')->pluck('id');
        }

        if ($doctorIds->isEmpty()) {
            return response()->json(['success' => true, 'date' => $date->toDateString(), 'slots' => []]);
        }

        // 2) Horarios activos para ese día
        $schedules = \App\Models\DoctorSchedule::whereIn('doctor_id', $doctorIds)
            ->where('day_of_week', $dayOfWeek)
            ->where('active', true)
            ->get()
            ->groupBy('doctor_id');

        if ($schedules->isEmpty()) {
            return response()->json(['success' => true, 'date' => $date->toDateString(), 'slots' => []]);
        }

        // 3) Citas ocupadas ese día
        $occupiedByDoctor = \App\Models\Appointment::whereIn('doctor_id', $doctorIds)
            ->where('date', $date->toDateString())
            ->get()
            ->groupBy('doctor_id')
            ->map(fn($items) => $items->pluck('time')->toArray());

        // 4) Nombres de médicos
        $doctorNames = \App\Models\User::whereIn('id', $doctorIds)->pluck('name', 'id');

        // 5) Construir slots [{time, doctor_id, doctor_name}]
        $result = [];
        foreach ($schedules as $doctorId => $doctorSchedules) {
            $occupiedTimes = $occupiedByDoctor->get($doctorId, []);
            foreach ($doctorSchedules as $sch) {
                $start = \Carbon\Carbon::parse($sch->start_time);
                $end   = \Carbon\Carbon::parse($sch->end_time);
                $step  = $sch->slot_minutes ?: 30;

                while ($start < $end) {
                    $time = $start->format('H:i');

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

        // Ordenamos por hora y luego por nombre
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
     * LÓGICA CENTRAL DE SLOTS:
     * Genera los slots disponibles por fecha (para TODOS los médicos con horario activo).
     * Devuelve: array de ["time" => "HH:MM", "doctor_id" => int, "doctor_name" => string]
     */

    private function buildAvailableSlotsForDate(Carbon $date): array
    {
        $dayOfWeek = $date->dayOfWeek;

        // Médicos con rol “Médico”
        $doctorIds = User::role('Médico')->pluck('id');
        if ($doctorIds->isEmpty()) return [];

        // Horarios activos por día
        $schedules = DoctorSchedule::whereIn('doctor_id', $doctorIds)
            ->where('day_of_week', $dayOfWeek)
            ->where('active', true)
            ->get()
            ->groupBy('doctor_id');
        if ($schedules->isEmpty()) return [];

        // Citas ocupadas ese día por médico (para quitar slots globalmente por médico)
        $occupiedByDoctor = Appointment::whereIn('doctor_id', $doctorIds)
            ->where('date', $date->toDateString())
            ->get()
            ->groupBy('doctor_id')
            ->map(fn($items) => $items->pluck('time')->toArray());

        // Horas ya ocupadas por este usuario y SUS dependientes
        $userPatientIds = Patient::where(function ($q) {
            $q->where('user_id', Auth::id())
                ->orWhere('owner_user_id', Auth::id());
        })
            ->pluck('id');

        $userBookedTimes = Appointment::whereIn('patient_id', $userPatientIds)
            ->where('date', $date->toDateString())
            ->pluck('time')
            ->toArray();


        // Mapa de nombre de médico
        $doctorNames = User::whereIn('id', $doctorIds)->pluck('name', 'id');

        $result = [];
        foreach ($schedules as $doctorId => $doctorSchedules) {
            foreach ($doctorSchedules as $sch) {
                $start = Carbon::parse($sch->start_time);
                $end   = Carbon::parse($sch->end_time);
                $step  = $sch->slot_minutes ?: 30;

                $occupiedTimes = $occupiedByDoctor->get($doctorId, []);

                while ($start < $end) {
                    $time = $start->format('H:i');

                    // Excluir si ocupado por cualquier paciente con ese médico
                    // o si el propio usuario ya tiene una cita a esa hora (con cualquier médico)
                    if (
                        !in_array($time, $occupiedTimes, true)
                        && !in_array($time, $userBookedTimes, true)
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
}

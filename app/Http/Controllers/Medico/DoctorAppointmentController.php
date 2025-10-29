<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DoctorAppointmentController extends Controller
{
    /**
     * (Opcional) listado simple de citas del médico.
     */
    public function index()
    {
        $appointments = Appointment::with(['patient', 'consultation'])
            ->where('doctor_id', Auth::id())
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return view('medico.appointments.index', compact('appointments'));
    }

    /**
     * Formulario para completar la consulta (diagnóstico/receta).
     * Prefill de "reason" con la consulta (si existe) o con las notas del appointment.
     */
    public function complete(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        if ($appointment->status === 'cancelada') {
            abort(403, 'La cita fue cancelada.');
        }

        $consultation = $appointment->consultation; // puede ser null
        $prefilledReason = $consultation?->reason ?? $appointment->notes ?? '';

        return view('medico.appointments.complete', [
            'appointment'     => $appointment,
            'patient'         => $appointment->patient,
            'consultation'    => $consultation,
            'prefilledReason' => $prefilledReason, // <<— valor para el textarea
        ]);
    }

    /**
     * Guarda/actualiza la consulta y marca la cita como completada.
     * Si el médico deja "reason" vacío, usamos las notas del appointment.
     */
    public function store(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        $data = $request->validate([
            'diagnosis'     => 'required|string|min:5',
            'prescription'  => 'nullable|string',
            'reason'        => 'nullable|string',
            'temperature'   => 'nullable|string|max:50',
            'pulse'         => 'nullable|string|max:50',
            'pressure'      => 'nullable|string|max:50',
            'weight'        => 'nullable|string|max:50',
        ], [
            'diagnosis.required' => 'El diagnóstico es obligatorio.',
            'diagnosis.min'      => 'El diagnóstico debe tener al menos 5 caracteres.',
        ]);

        // Si el médico no llenó "reason", tomar las notas del cliente
        if (empty($data['reason'])) {
            $data['reason'] = $appointment->notes ?? null;
        }

        // Crea o actualiza una sola consulta por cita
        MedicalConsultation::updateOrCreate(
            ['appointment_id' => $appointment->id],
            array_merge($data, [
                'doctor_id'  => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
            ])
        );

        // Marca la cita como completada (si aún no lo está)
        if ($appointment->status !== 'completada') {
            $appointment->update(['status' => 'completada']);
        }

        return redirect()
            ->route('medico.dashboard')
            ->with('success', 'Consulta guardada y cita marcada como completada.');
    }

    /**
     * Asegura que la cita pertenezca al médico autenticado.
     */
    private function authorizeAppointment(Appointment $appointment): void
    {
        if ((int) $appointment->doctor_id !== (int) Auth::id()) {
            throw ValidationException::withMessages([
                'appointment' => 'No autorizado para modificar esta cita.',
            ]);
        }
    }
}

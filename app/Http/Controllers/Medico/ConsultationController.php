<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ConsultationController extends Controller
{
    /**
     * Crear (o editar si ya existe) la consulta asociada a una cita.
     * Vista con formulario para diagnóstico, receta y signos vitales.
     */
    public function create(Appointment $appointment)
    {
        // Seguridad: la cita debe pertenecer al médico autenticado
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        // Si ya existe, redirige a editar
        $existing = MedicalConsultation::where('appointment_id', $appointment->id)->first();
        if ($existing) {
            return redirect()->route('medico.consultations.edit', $existing);
        }

        return view('medico.consultations.create', [
            'appointment' => $appointment,
            'patient'     => $appointment->patient,
        ]);
    }

    public function store(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        $data = $request->validate([
            'reason'       => 'nullable|string|max:2000',
            'diagnosis'    => 'required|string|max:5000',
            'prescription' => 'required|string|max:5000',
            'temperature'  => 'nullable|string|max:50',
            'pulse'        => 'nullable|string|max:50',
            'pressure'     => 'nullable|string|max:50',
            'weight'       => 'nullable|string|max:50',
        ]);

        // Evitar duplicado
        if (MedicalConsultation::where('appointment_id', $appointment->id)->exists()) {
            throw ValidationException::withMessages(['appointment_id' => 'Esta cita ya tiene registro clínico.']);
        }

        MedicalConsultation::create([
            'appointment_id' => $appointment->id,
            'patient_id'     => $appointment->patient_id,
            'doctor_id'      => $appointment->doctor_id,
            ...$data,
        ]);

        // Opcional: marcar cita como completada
        $appointment->update(['status' => 'completada']);

        return redirect()
            ->route('medico.dashboard')
            ->with('success', 'Consulta registrada y cita marcada como completada.');
    }

    public function edit(MedicalConsultation $consultation)
    {
        if ($consultation->doctor_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        return view('medico.consultations.edit', [
            'consultation' => $consultation,
            'appointment'  => $consultation->appointment,
            'patient'      => $consultation->patient,
        ]);
    }

    public function update(Request $request, MedicalConsultation $consultation)
    {
        if ($consultation->doctor_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        $data = $request->validate([
            'reason'       => 'nullable|string|max:2000',
            'diagnosis'    => 'required|string|max:5000',
            'prescription' => 'required|string|max:5000',
            'temperature'  => 'nullable|string|max:50',
            'pulse'        => 'nullable|string|max:50',
            'pressure'     => 'nullable|string|max:50',
            'weight'       => 'nullable|string|max:50',
        ]);

        $consultation->update($data);

        return redirect()
            ->route('medico.dashboard')
            ->with('success', 'Consulta actualizada correctamente.');
    }

    /**
     * Vista de solo lectura (para médico). Luego haremos una pública para paciente.
     */
    public function show(MedicalConsultation $consultation)
    {
        if ($consultation->doctor_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        return view('medico.consultations.show', [
            'consultation' => $consultation,
            'appointment'  => $consultation->appointment,
            'patient'      => $consultation->patient,
        ]);
    }
}

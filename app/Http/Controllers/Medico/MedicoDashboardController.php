<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MedicoDashboardController extends Controller
{
    public function index()
    {
        $doctorId = Auth::id();

        $pendingAppointments = Appointment::with(['patient', 'consultation'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'pendiente')
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $completedAppointments = Appointment::with(['patient'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'completada')
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        $canceledAppointments = Appointment::with(['patient'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'cancelada')
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        return view('medico.dashboard', compact(
            'pendingAppointments',
            'completedAppointments',
            'canceledAppointments'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:cancelada,completada,pendiente',
        ]);

        $appointment = Appointment::with(['patient'])->findOrFail($id);

        if ((int) $appointment->doctor_id !== (int) Auth::id()) {
            throw ValidationException::withMessages([
                'appointment' => 'No autorizado para modificar esta cita.',
            ]);
        }

        // Completar desde el dashboard NO: redirige al formulario de completar
        if ($request->status === 'completada') {
            return redirect()
                ->route('medico.appointments.complete', $appointment)
                ->with('error', 'Para completar debes registrar diagnóstico y receta.');
        }

        if ($request->status === 'pendiente') {
            $appointment->update(['status' => 'pendiente']);
            return back()->with('success', 'La cita fue puesta como pendiente nuevamente.');
        }

        if ($request->status === 'cancelada') {
            $appointment->update(['status' => 'cancelada']);
            return back()->with('success', 'La cita fue cancelada correctamente.');
        }

        return back()->with('error', 'Acción no permitida.');
    }
}

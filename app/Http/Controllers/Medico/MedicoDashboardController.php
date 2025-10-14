<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicoDashboardController extends Controller
{
    /**
     * Muestra el panel principal del médico con sus citas.
     */
    public function index()
    {
        $doctor = Auth::user();

        // Obtener todas las citas del médico autenticado
        $appointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->orderBy('date', 'asc')
            ->get();

        // Separar citas por estado
        $pendingAppointments = $appointments->where('status', 'pendiente');
        $completedAppointments = $appointments->where('status', 'completada');
        $canceledAppointments = $appointments->where('status', 'cancelada');

        return view('medico.dashboard', compact(
            'doctor',
            'pendingAppointments',
            'completedAppointments',
            'canceledAppointments'
        ));
    }

    /**
     * Permite al médico actualizar el estado de una cita (completar o cancelar).
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pendiente,completada,cancelada',
        ]);

        // Verificar que la cita pertenezca al médico autenticado
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403, 'No autorizado para modificar esta cita.');
        }

        $appointment->status = $request->status;
        $appointment->save();

        return redirect()->route('medico.dashboard')->with('success', 'Estado de la cita actualizado correctamente.');
    }
}

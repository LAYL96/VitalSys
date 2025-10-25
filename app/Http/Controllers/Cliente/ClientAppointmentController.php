<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAppointmentController extends Controller
{
    /**
     * Muestra todas las citas del cliente autenticado.
     * Cada cliente verá únicamente sus propias citas.
     */
    public function index()
    {
        $client = Auth::user();

        // Obtener las citas del cliente con el nombre del médico asociado
        $appointments = Appointment::with(['doctor'])
            ->where('patient_id', $client->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('cliente.appointments.index', compact('appointments'));
    }

    /**
     * Muestra el formulario para agendar una nueva cita.
     * Se listan todos los médicos registrados en el sistema.
     */
    public function create()
    {
        // Obtener usuarios con el rol "Médico" (usando Spatie)
        $doctors = User::role('Médico')->get();

        return view('cliente.appointments.create', compact('doctors'));
    }

    /**
     * Guarda la cita en la base de datos.
     * El paciente autenticado (cliente) queda vinculado a la cita creada.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        $client = Auth::user();

        Appointment::create([
            'patient_id' => $client->id,
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'pendiente',
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('cliente.appointments.index')
            ->with('success', '¡Tu cita ha sido agendada correctamente!');
    }
}

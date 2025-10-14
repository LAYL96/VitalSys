<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class MedicoDashboardController extends Controller
{
    /**
     * Muestra el panel principal del médico con estadísticas generales.
     */
    public function index()
    {

        // Total de pacientes registrados
        $totalPatients = Patient::count();

        // Citas pendientes del día
        $todayAppointments = Appointment::whereDate('date', now()->toDateString())
            ->where('status', 'pendiente')
            ->count();

        // Citas totales
        $totalAppointments = Appointment::count();

        return view('medico.dashboard', compact('totalPatients', 'todayAppointments', 'totalAppointments'));
    }
}

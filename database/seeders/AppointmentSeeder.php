<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::role('Médico')->get();
        $patients = Patient::all();

        foreach ($patients as $index => $patient) {
            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctors[$index % $doctors->count()]->id, // Asigna médico de forma cíclica
                'date' => Carbon::now()->addDays(rand(1, 10)),
                'time' => Carbon::createFromTime(rand(8, 17), 0, 0),
                'status' => 'pendiente',
                'reason' => 'Consulta general',
                'notes' => 'Paciente programado para revisión médica.',
            ]);
        }
    }
}

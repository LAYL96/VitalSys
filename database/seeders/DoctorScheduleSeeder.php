<?php

namespace Database\Seeders;

use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sabado    = User::where('email', 'sabado@vitalsys.com')->first();
        $domingo   = User::where('email', 'domingo@vitalsys.com')->first();
        $semana    = User::where('email', 'semana@vitalsys.com')->first();
        $cobertura = User::where('email', 'cobertura@vitalsys.com')->first();

        DoctorSchedule::truncate();

        // Sábado (6)
        DoctorSchedule::create([
            'doctor_id' => $sabado->id,
            'day_of_week' => 6,
            'start_time' => '09:00',
            'end_time' => '16:00',
            'slot_minutes' => 30,
            'active' => true,
        ]);

        // Domingo (0)
        DoctorSchedule::create([
            'doctor_id' => $domingo->id,
            'day_of_week' => 0,
            'start_time' => '10:00',
            'end_time' => '17:00',
            'slot_minutes' => 30,
            'active' => true,
        ]);

        // Lunes a Viernes (1..5)
        foreach ([1, 2, 3, 4, 5] as $day) {
            // Doctor Semana (activo)
            DoctorSchedule::create([
                'doctor_id' => $semana->id,
                'day_of_week' => $day,
                'start_time' => '09:00',
                'end_time' => '17:00',
                'slot_minutes' => 30,
                'active' => true,
            ]);

            // Dra Cobertura (inactiva por defecto)
            DoctorSchedule::create([
                'doctor_id' => $cobertura->id,
                'day_of_week' => $day,
                'start_time' => '09:00',
                'end_time' => '17:00',
                'slot_minutes' => 30,
                'active' => false,
            ]);
        }
    }
}

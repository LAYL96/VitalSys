<?php

namespace Database\Seeders;

use App\Models\DoctorScheduleOverride;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorScheduleOverridesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semana    = User::where('email', 'semana@vitalsys.com')->first();
        $cobertura = User::where('email', 'cobertura@vitalsys.com')->first();

        $randomRestDay = Carbon::now()->addDays(rand(1, 10))->toDateString();

        // Desactivar al Dr. Semana ese día
        DoctorScheduleOverride::create([
            'doctor_id' => $semana->id,
            'date' => $randomRestDay,
            'active' => false,
            'note' => 'Descanso médico'
        ]);

        // Activar cobertura ese mismo día
        DoctorScheduleOverride::create([
            'doctor_id' => $cobertura->id,
            'date' => $randomRestDay,
            'start_time' => '09:00',
            'end_time' => '17:00',
            'slot_minutes' => 30,
            'active' => true,
            'note' => 'Cobertura por descanso médico'
        ]);
    }
}

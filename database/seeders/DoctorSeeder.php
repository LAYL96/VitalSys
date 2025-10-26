<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'Dra. Sábado',
                'email' => 'sabado@vitalsys.com',
            ],
            [
                'name' => 'Dr. Domingo',
                'email' => 'domingo@vitalsys.com',
            ],
            [
                'name' => 'Dr. Semana',
                'email' => 'semana@vitalsys.com',
            ],
            [
                'name' => 'Dra. Cobertura',
                'email' => 'cobertura@vitalsys.com',
            ],
        ];

        foreach ($doctors as $doctor) {

            $user = User::firstOrCreate(
                ['email' => $doctor['email']],
                [
                    'name'     => $doctor['name'],
                    'password' => Hash::make('password123'),
                ]
            );

            // Asignar rol Médico usando Spatie
            $user->syncRoles(['Médico']);
        }
    }
}

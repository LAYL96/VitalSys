<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 3 médicos con el rol "Médico"
        $doctors = [
            [
                'name' => 'Dr. Carlos Pérez',
                'email' => 'carlos.perez@vitalsys.com',
            ],
            [
                'name' => 'Dra. Ana López',
                'email' => 'ana.lopez@vitalsys.com',
            ],
            [
                'name' => 'Dr. Luis Morales',
                'email' => 'luis.morales@vitalsys.com',
            ],
        ];

        foreach ($doctors as $doctor) {
            $user = User::create([
                'name' => $doctor['name'],
                'email' => $doctor['email'],
                'password' => Hash::make('12345678'),
            ]);

            // Asignar rol de Médico
            $user->assignRole('Médico');
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'dpi' => '1234567890101',
                'name' => 'María',
                'lastname' => 'Gómez',
                'birthdate' => Carbon::parse('1990-05-14'),
                'gender' => 'Femenino',
                'phone' => '55512345',
                'email' => 'maria.gomez@example.com',
                'address' => 'Zona 1, Ciudad de Guatemala',
            ],
            [
                'dpi' => '2345678901212',
                'name' => 'José',
                'lastname' => 'Ramírez',
                'birthdate' => Carbon::parse('1985-09-22'),
                'gender' => 'Masculino',
                'phone' => '55567890',
                'email' => 'jose.ramirez@example.com',
                'address' => 'Zona 5, Mixco',
            ],
            [
                'dpi' => '3456789012323',
                'name' => 'Lucía',
                'lastname' => 'Martínez',
                'birthdate' => Carbon::parse('1998-01-30'),
                'gender' => 'Femenino',
                'phone' => '55598765',
                'email' => 'lucia.martinez@example.com',
                'address' => 'Zona 10, Guatemala',
            ],
        ];

        foreach ($patients as $data) {
            Patient::create($data);
        }
    }
}

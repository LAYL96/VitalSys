<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Administrador
        $admin = User::create([
            'name' => 'Administrador General',
            'email' => 'admin@vitalsys.com',
            'password' => Hash::make('admin123'),
        ]);
        $admin->assignRole('Administrador');

        // 🔹 Empleado
        $empleado = User::create([
            'name' => 'Empleado Ventas',
            'email' => 'empleado@vitalsys.com',
            'password' => Hash::make('empleado123'),
        ]);
        $empleado->assignRole('Empleado');

        // 🔹 Médico
        $medico = User::create([
            'name' => 'Dr. Luis Morales',
            'email' => 'medico@vitalsys.com',
            'password' => Hash::make('medico123'),
        ]);
        $medico->assignRole('Médico');

        // 🔹 Cliente
        $cliente = User::create([
            'name' => 'Cliente Ejemplo',
            'email' => 'cliente@vitalsys.com',
            'password' => Hash::make('cliente123'),
        ]);
        $cliente->assignRole('Cliente');
    }
}

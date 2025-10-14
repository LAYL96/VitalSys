<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Administrador', 'guard_name' => 'web']);
        Role::create(['name' => 'Empleado', 'guard_name' => 'web']);
        Role::create(['name' => 'Médico', 'guard_name' => 'web']);
        Role::create(['name' => 'Cliente', 'guard_name' => 'web']);
    }
}

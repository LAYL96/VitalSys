<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Crear 10 proveedores de prueba
        for ($i = 0; $i < 5; $i++) {
            Supplier::create([
                'name' => $faker->company,
                'contact_info' => $faker->phoneNumber . ' | ' . $faker->email,
            ]);
        }
    }
}

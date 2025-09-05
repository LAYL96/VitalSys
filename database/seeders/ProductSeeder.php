<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Obtener todas las categorías y proveedores
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Generar 5 productos
        for ($i = 0; $i < 5; $i++) {
            Product::create([
                'name' => $faker->words(2, true),
                'sku' => strtoupper($faker->bothify('PROD-###??')),
                'category_id' => $categories->random()->id ?? null,
                'supplier_id' => $suppliers->random()->id ?? null,
                'description' => $faker->sentence(10),
                'price' => $faker->randomFloat(2, 5, 500), // precio entre 5 y 500
                'stock' => $faker->numberBetween(0, 100),
                'min_stock' => $faker->numberBetween(1, 10),
                'expiration_date' => $faker->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
                'status' => $faker->randomElement(['activo', 'descontinuado', 'reservado']),
            ]);
        }
    }
}

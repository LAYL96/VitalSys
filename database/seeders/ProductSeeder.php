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
        /*
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
        */

        $faker = Faker::create('es_GT'); // Español Guatemala

        // Obtener todas las categorías y proveedores
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Lista de productos típicos de farmacia
        $productosEjemplo = [
            ['name' => 'Paracetamol 500mg', 'descripcion' => 'Analgésico y antipirético para el alivio del dolor y la fiebre.'],
            ['name' => 'Ibuprofeno 400mg', 'descripcion' => 'Antiinflamatorio no esteroide para dolores musculares y fiebre.'],
            ['name' => 'Vitamina C 1000mg', 'descripcion' => 'Suplemento para reforzar el sistema inmunológico.'],
            ['name' => 'Omeprazol 20mg', 'descripcion' => 'Tratamiento para acidez estomacal y reflujo gástrico.'],
            ['name' => 'Ácido Fólico 5mg', 'descripcion' => 'Suplemento esencial durante el embarazo y para la salud celular.'],
        ];

        foreach ($productosEjemplo as $producto) {
            Product::create([
                'name' => $producto['name'],
                'sku' => strtoupper($faker->bothify('PROD-###??')),
                'category_id' => $categories->random()->id ?? null,
                'supplier_id' => $suppliers->random()->id ?? null,
                'description' => $producto['descripcion'],
                'price' => $faker->randomFloat(2, 10, 500), // precios entre Q10.00 y Q500.00
                'stock' => $faker->numberBetween(0, 100),
                'min_stock' => $faker->numberBetween(1, 10),
                'expiration_date' => $faker->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
                'status' => $faker->randomElement(['activo', 'descontinuado', 'reservado']),
                'image' => 'products/' . $faker->randomElement([
                    'paracetamol.jpg',
                    'ibuprofeno.jpg',
                    'vitamina-c.jpg',
                    'omeprazol.jpg',
                    'folico.jpg'
                ]),
            ]);
        }
    }
}

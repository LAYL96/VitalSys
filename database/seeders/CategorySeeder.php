<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorías de ejemplo
        $categories = [
            [
                'name' => 'Medicamentos',
                'description' => 'Categoría de medicamentos de uso general y especializado.'
            ],
            [
                'name' => 'Equipos Médicos',
                'description' => 'Instrumental y equipos utilizados en clínicas y hospitales.'
            ],
            [
                'name' => 'Suplementos',
                'description' => 'Vitaminas, minerales y suplementos nutricionales.'
            ],
        ];

        // Insertar cada categoría en la BD
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

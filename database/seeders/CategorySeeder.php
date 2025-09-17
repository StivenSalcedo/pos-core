<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    public function run()
    {
        $categories = [
            "Electrónica",
            "Ropa",
            "Alimentos",
            "Hogar",
            "Juguetes",
            "Automoción",
            "Deportes",
            "Belleza",
            "Electrodomésticos",
            "Libros",
            "Muebles",
            "Joyería",
            "Electrónica de consumo",
            "Productos para mascotas",
            "Herramientas",
            "Suministros para oficina",
            "Arte y artesanía",
            "Equipamiento para exteriores",
            "Instrumentos musicales",
            "Productos para bebés",
        ];

        foreach ($categories as $key => $value) {
            Category::create([
                'name' => $value
            ]);
        }

    }
}

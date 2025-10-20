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
            "General"
        ];

        foreach ($categories as $key => $value) {
            Category::create([
                'name' => $value
            ]);
        }

    }
}

<?php

namespace Database\Factories;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory {

    public function definition() {

        $barcode = $this->faker->unique()->randomNumber(7, true);

        return [
            'barcode' => $barcode,
            'reference' => $barcode,
            'category_id' => Category::all()->random()->id,
            'name' => $this->faker->name(),
            'cost' => $this->faker->randomElement([10000, 20000, 30000, 40000, 50000]),
            'price' => $this->faker->randomElement([60000, 70000, 80000, 90000, 100000]),
            'stock' => $this->faker->randomElement([10, 20, 30, 40, 50]),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseDetailFactory extends Factory {

    public function definition() {
        return [
            'amount' => 2,
            'price' => 2,
            'product_id' => Product::all()->random()->id,
        ];
    }
}

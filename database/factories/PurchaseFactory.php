<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory {

    public function definition() {
        return [
            'total' => 10000,
            'provider_id' => Provider::all()->random()->id
        ];
    }
}

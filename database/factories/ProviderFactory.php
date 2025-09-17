<?php

namespace Database\Factories;

use App\Enums\TypesProviders;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProviderFactory extends Factory {

    public function definition(){
        return [
            'no_identification' => $this->faker->unique()->randomNumber(7, true),
            'name' => $this->faker->company(),
            'phone' => Str::limit(Str::replace('+', '', $this->faker->e164PhoneNumber()), 10, ''),
            'direction' => $this->faker->streetAddress(),
            'type' =>  $this->faker->randomElement(TypesProviders::getCases()),
            'description' =>  $this->faker->text(),
        ];
    }
}

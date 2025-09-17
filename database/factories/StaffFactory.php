<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class StaffFactory extends Factory {

    public function definition() {
        return [
            'no_identification' => $this->faker->randomNumber(7, true),
            'names' => $this->faker->name(),
            'direction' => $this->faker->streetAddress(),
            'phone' => Str::limit(Str::replace('+', '', $this->faker->e164PhoneNumber()), 10, ''),
            'email' =>  $this->faker->unique()->email(),
            'description' =>  $this->faker->word(),
        ];
    }
}

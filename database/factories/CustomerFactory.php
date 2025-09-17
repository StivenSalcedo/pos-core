<?php

namespace Database\Factories;

use App\Models\IdentificationDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory {

    public function definition() {
        return [
            'identification_document_id' => IdentificationDocument::enabled()->get()->random()->id,
            'no_identification' => $this->faker->randomNumber(7, true),
            'names' => $this->faker->name(),
            'direction' => $this->faker->streetAddress(),
            'phone' => Str::limit(Str::replace('+', '', $this->faker->e164PhoneNumber()), 10, ''),
            'email' =>  $this->faker->unique()->email(),
        ];
    }
}

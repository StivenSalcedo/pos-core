<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Output>
 */
class OutputFactory extends Factory {

    public function definition() {
        return [
            'reason' => $this->faker->word(),
            'date' => Carbon::today(),
            'price' => 40000,
            'description' => $this->faker->paragraph(),
            'user_id' => User::all()->random()->id

        ];
    }
}

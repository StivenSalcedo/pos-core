<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payroll>
 */
class PayrollFactory extends Factory {

    public function definition() {
        return [
            'price' => 1000,
            'description' => 'Pago mes de mayo',
            'staff_id' => Staff::all()->random()->id,
            'user_id' => User::all()->random()->id
        ];
    }
}

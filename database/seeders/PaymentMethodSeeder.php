<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder {

    public function run(){

        PaymentMethod::create([
            'name' => 'Efectivo',
            'code' => '10',
            'status' => PaymentMethod::ACTIVE,
        ]);

        PaymentMethod::create([
            'name' => 'Tarjeta crédito',
            'code' => '48',
            'status' => PaymentMethod::ACTIVE,
        ]);

        PaymentMethod::create([
            'name' => 'Tarjeta Débito',
            'code' => '49',
            'status' => PaymentMethod::ACTIVE,
        ]);

        PaymentMethod::create([
            'name' => 'Transferencia',
            'code' => '47',
            'status' => PaymentMethod::ACTIVE,
        ]);

    }
}

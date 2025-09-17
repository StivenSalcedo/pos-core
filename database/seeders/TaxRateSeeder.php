<?php

namespace Database\Seeders;

use App\Models\TaxRate;
use Illuminate\Database\Seeder;

class TaxRateSeeder extends Seeder
{
    public function run()
    {
        TaxRate::create([
            'name' => 'Excluido de IVA',
            'rate' => '0.00',
            'tribute_id' => 1
        ]);

        TaxRate::create([
            'name' => 'Bienes/Servicios',
            'rate' => '5.00',
            'tribute_id' => 1
        ]);

        TaxRate::create([
            'name' => 'Tarifa General',
            'rate' => '19.00',
            'default' => 1,
            'tribute_id' => 1
        ]);

        TaxRate::create([
            'name' => 'Impuesto al consumo',
            'rate' => '8.00',
            'default' => 1,
            'tribute_id' => 2
        ]);

        TaxRate::create([
            'name' => 'Ultraprocesados bebidas menor a 6 gr',
            'rate' => '0.00',
            'has_percentage' => 0,
            'tribute_id' => 3
        ]);


        TaxRate::create([
            'name' => 'Ultraprocesados bebidas mayor o igual a 6 gr y menor a 10 gr',
            'rate' => '28',
            'has_percentage' => 0,
            'tribute_id' => 3
        ]);

        TaxRate::create([
            'name' => 'Ultraprocesados bebidas mayor o igual a 10 gr',
            'rate' => '55',
            'has_percentage' => 0,
            'tribute_id' => 3
        ]);

        TaxRate::create([
            'name' => 'Comestibles ultraprocesados',
            'rate' => '15.00',
            'has_percentage' => 1,
            'tribute_id' => 4
        ]);

        TaxRate::create([
            'name' => 'Excento de IVA',
            'rate' => '0.00',
            'has_percentage' => 1,
            'tribute_id' => 1
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\TaxRate;
use App\Models\Tribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TributeSeeder extends Seeder
{
    public function run()
    {
        Tribute::create([
            'api_tribute_id' => 1,
            'name' => 'IVA',
            'description' => 'Impuesto sobre la Ventas',
            'status' => '0',
        ]);

        Tribute::create([
            'api_tribute_id' => 4,
            'name' => 'INC',
            'description' => 'Impuesto Nacional al Consumo',
            'status' => '0',
        ]);

        Tribute::create([
            'api_tribute_id' => 0,
            'name' => 'IBUA',
            'description' => 'Ultraprocesados Bebidas',
            'status' => '0',
        ]);

        Tribute::create([
            'api_tribute_id' => 0,
            'name' => 'ICUI',
            'description' => 'Ultraprocesados comestibles',
            'status' => '0',
        ]);
    }
}

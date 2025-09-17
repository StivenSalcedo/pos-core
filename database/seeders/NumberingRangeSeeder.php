<?php

namespace Database\Seeders;

use App\Models\NumberingRange;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NumberingRangeSeeder extends Seeder {
   
    public function run() {
        
        NumberingRange::create([
            'prefix' => 'POS',
            'from' => 1,
            'to' => 10000000,
            'current' => 1,
            'expire' => now()->addYears(20),
            'status' => '0',
        ]);

    }
}

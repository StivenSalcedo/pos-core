<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $name = 'Mesa';

        for ($i=1; $i < 21 ; $i++) {
            Order::create([
                'name' => "$name $i",
                'customer' => [],
                'products' => [],
                'total' => 0,
            ]);
        }
    }
}

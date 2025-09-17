<?php

namespace App\Console\Commands;

use App\Models\Order;
use Database\Seeders\OrderSeeder;
use Illuminate\Console\Command;

class ResetOrders extends Command
{
    protected $signature = 'orders:reset';

    protected $description = 'Borrar todas las ordenes y las vuelve a crear';

    public function handle()
    {
        $this->info('Iniciando ejecución');

        Order::truncate();
        $this->call(OrderSeeder::class);

        $this->info('fin de la ejecución');
    }
}

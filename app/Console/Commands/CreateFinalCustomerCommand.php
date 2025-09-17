<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class CreateFinalCustomerCommand extends Command
{
    protected $signature = 'customer:final';

    protected $description = 'Cambia el cliente generico por el consumidor final final.';

    public function handle()
    {
        $this->info('Actualizando cliente consumidor final...');

        $customer = Customer::where('no_identification', '999999999')->first();

        $customer->no_identification = '222222222222';
        $customer->names = 'Consumidor final';

        $customer->save();

        $this->info('Cliente consumidor final actualizado correctamente.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ElectronicPOSStep2Command extends Command
{
    protected $signature = 'pos:add-step2';

    protected $description = 'Ejecuta los scripts necesarios para usar pos electronico';

    public function handle()
    {
        $this->comment('Iniciando ejecucion');

        Artisan::call('migrate', ['--force' => true]);

        $this->comment('Ejecucion finalizada');
    }
}

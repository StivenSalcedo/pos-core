<?php

namespace App\Console\Commands;

use App\Models\Module;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class ModuleDailySales extends Command
{
    protected $signature = 'module:add';

    protected $description = 'Agrega el permiso del modulo de reporte de venta diarias y agrega el modulo a los modulos';

    public function handle()
    {
        $this->comment('Iniciando tarea');
        $this->newLine();

        $this->addModule();

        $this->newLine();
        $this->comment('Fin de la tarea');
    }

    protected function addModule()
    {
        $this->comment('Agregando el modulo de reporte de ventas diarias a los modulos');
        $this->newLine();

        Module::create([
            'name' => 'reporte de ventas diarias',
            'is_enabled' => false
        ]);

        $this->comment('Se agrego el modulo de reporte de ventas diarias a los modulos ');
        $this->newLine();
    }

}

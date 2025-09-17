<?php

namespace App\Console\Commands;

use App\Models\Module;
use App\Models\Tribute;
use Illuminate\Console\Command;

class AddModuleTaxRate extends Command
{
    protected $signature = 'add-module:impuestos';

    protected $description = 'Agregar el modulo de impuestos a los módulos';

    public function handle()
    {
        $this->comment('Iniciando tarea');
        $this->newLine();

        $this->addModule();
        $this->activeAllTributes();

        $this->newLine();
        $this->comment('Fin de la tarea');
    }

    protected function addModule()
    {
        $this->comment('Agregando el modulo de impuestos a los modulos');
        $this->newLine();

        Module::create([
            'name' => 'impuestos',
            'is_enabled' => false
        ]);

        $this->comment('Se agrego el modulo de impuestos a los modulos ');
        $this->newLine();
    }

    protected function activeAllTributes()
    {
        $this->comment('Activando todos los tributos');
        $this->newLine();

        Tribute::query()->update(['status' => '0']);

        $this->comment('Se activaron todos los tributos');
        $this->newLine();
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Module;
use Illuminate\Console\Command;

class AddInventoryFuntionalityCommand extends Command
{
    protected $signature = 'add-permission:inventory {--force}';

    protected $description = 'Agrega el permiso de llevar inventario';

    public function handle() {

        if (!$this->option('force') && !$this->confirm('¿Está seguro de ejecutar este comando?')) {
            $this->info('Operación cancelada por el usuario');
            return;
        }

        $this->newLine();

        $this->info('Agregar columna de funcionalidad');

        $this->call('migrate', [
            '--path' => 'database/migrations/2024_07_29_105301_add_is_functionality_column_to_modules_table.php',
            '--force' => true
        ]);

        $this->info('Columna agregada correctamente');

        $this->newLine();

        $this->info('Creando permiso de funcionalidad');

        Module::create([
            'name' => 'inventario',
            'is_functionality' => true,
            'is_enabled' => true,
        ]);

        $this->info('Permiso de funcionalidad agregado correctamente');
    }

}

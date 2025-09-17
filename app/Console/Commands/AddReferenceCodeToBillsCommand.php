<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddReferenceCodeToBillsCommand extends Command
{
    protected $signature = 'bills:reference-code {--force}';

    protected $description = 'Agrega el codigo de referencia a las facturas';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('¿Está seguro de ejecutar el comando?')) {
            $this->info('Operación cancelada');
            return;
        }

        $this->call('migrate', [
            '--path' => 'database/migrations/2024_08_26_162343_add_reference_code_column_to_bills_table.php',
            '--force' => true
        ]);

        $this->info('Campo codigo de referencia agregado correctamente');
    }
}

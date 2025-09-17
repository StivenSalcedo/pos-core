<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\IdentificationDocument;
use Illuminate\Console\Command;

class AddDVToCustomersCommand extends Command
{
    protected $signature = 'customers:add-dv {--force}';

    protected $description = 'Agrega el digito de verificación a los clientes que no lo tienen';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('¿Está seguro de ejecutar el comando?')) {
            $this->info('Operación cancelada');
            return;
        }

        $this->newLine();
        $this->info('Agregando columna dv a la tabla customers');
        $this->call('migrate', [
            '--path' => 'database/migrations/2024_08_08_145038_add_dv_column_to_customers_table.php',
            '--force' => true,
        ]);

        $this->newLine();
        $this->info('Agregando digito de verificación a los clientes');
        $customers = Customer::all();
        $invalidIdentifications = [];
        foreach ($customers as $customer) {
            if(! in_array($customer->identification_document_id, IdentificationDocument::FOREING_DOCUMENTS))
            {
                if (preg_match('/^\d+$/', $customer->no_identification)) {
                    if ($customer->identification_document_id === IdentificationDocument::NIT) {
                        $customer->dv = $this->calculateVerificationDigit($customer->no_identification);
                    }else{
                        $customer->dv = null;
                    }

                    $customer->save();
                }else{
                    $invalidIdentifications[] = [
                        'id' => $customer->id,
                        'identification_document' => $customer->identificationDocument->name,
                        'identification' => $customer->no_identification,
                    ];
                }
            }
        }

        $this->newLine();
        $this->info('Clientes actualizados correctamente');

        $this->newLine();
        $this->info('Clientes con identificaciones invalidas');
        $this->table(['ID', 'Tipo de identificación', 'Identificación'], $invalidIdentifications);

    }

    protected function calculateVerificationDigit($myNit)
    {
        $vpri = [
            3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71,
        ];

        $myNit = str_replace([' ', ',', '.', '-'], '', $myNit);

        if (! is_numeric($myNit)) {
            return '';
        }

        $z = strlen($myNit);
        $x = 0;

        for ($i = 0; $i < $z; $i++) {
            $y = substr($myNit, $i, 1);
            $x += $y * $vpri[$z - $i - 1];
        }

        $y = $x % 11;

        return $y > 1 ? 11 - $y : $y;
    }
}

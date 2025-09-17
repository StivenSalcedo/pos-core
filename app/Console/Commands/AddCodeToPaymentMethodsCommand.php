<?php

namespace App\Console\Commands;

use App\Models\PaymentMethod;
use Illuminate\Console\Command;

class AddCodeToPaymentMethodsCommand extends Command
{
    protected $signature = 'payment-methods:add-code {--force}';

    protected $description = 'Agrega el codigo a los metodos de pago para ser enviado junto con la factura a FACTUS';

    public function handle()
    {
        // verificar si se desea forzar la ejecucion y si esta seguro de ejecutar el comando
        if (!$this->option('force') && !$this->confirm('¿Está seguro de ejecutar el comando?')) {
            $this->info('Operación cancelada');
            return;
        }

        $this->newLine();
        $this->info('Agregando columna code a la tabla payment_methods');
        $this->call('migrate', [
            '--path' => 'database/migrations/2024_08_08_091935_add_code_column_to_payment_methods_table.php',
            '--force' => true,
        ]);

        $paymentMethods = PaymentMethod::all();

        $codes = [
            1 => '10',
            2 => '48',
            3 => '49',
            4 => '47',
        ];

        $this->newLine();
        $this->info('Agregando códigos a los métodos de pago');
        foreach ($paymentMethods as $paymentMethod) {
            $paymentMethod->code = $codes[$paymentMethod->id];
            $paymentMethod->save();
        }

        $this->newLine();
        $this->info('Códigos agregados correctamente');

        $this->newLine();
        $this->info('Operación finalizada');
    }
}

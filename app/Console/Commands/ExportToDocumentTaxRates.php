<?php

namespace App\Console\Commands;

use App\Models\Bill;
use App\Models\DetailBill;
use App\Models\DocumentTaxRate;
use App\Models\Product;
use App\Models\TaxRate;
use App\Models\Tribute;
use App\Services\DocumentTaxService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ExportToDocumentTaxRates extends Command
{
    protected $signature = 'export:tax-rates';

    protected $description = 'Agrege multiples impuestos a los productos';

    public function handle()
    {
        $this->comment('Ejecutando');

        $this->addMigrations1();
        $this->addNewTributesAndTaxes();
        $this->exportTaxProducts();
        $this->addMigrations2();
        $this->exportDetailBill();
        $this->exportBills();
        $this->addMigreation3();

        $this->comment('Terminado');

        return Command::SUCCESS;
    }

    protected function addNewTributesAndTaxes()
    {
        $this->comment('Creando nuevos tributos y impuestos');

        $tribute = Tribute::create([
            'name' => 'IBUA',
            'description' => 'Ultraprocesados Bebidas',
            'status' => '0',
        ]);

        TaxRate::create([
            'name' => 'Ultraprocesados bebidas menor a 6 gr',
            'rate' => '0.00',
            'has_percentage' => 0,
            'tribute_id' => $tribute->id
        ]);


        TaxRate::create([
            'name' => 'Ultraprocesados bebidas mayor o igual a 6 gr y menor a 10 gr',
            'rate' => '28',
            'has_percentage' => 0,
            'tribute_id' => $tribute->id
        ]);

        TaxRate::create([
            'name' => 'Ultraprocesados bebidas mayor o igual a 10 gr',
            'rate' => '55',
            'has_percentage' => 0,
            'tribute_id' => $tribute->id
        ]);

        $tribute = Tribute::create([
            'name' => 'ICUI',
            'description' => 'Ultraprocesados comestibles',
            'status' => '0',
        ]);

        TaxRate::create([
            'name' => 'Comestibles ultraprocesados',
            'rate' => '15.00',
            'has_percentage' => 1,
            'tribute_id' => $tribute->id
        ]);

        TaxRate::create([
            'name' => 'Excento de IVA',
            'rate' => '0.00',
            'has_percentage' => 1,
            'tribute_id' => 1
        ]);

        $taxRate = TaxRate::find(1);
        $taxRate->name='Excluido de IVA';
        $taxRate->save();

        $this->comment('Tributos y impuestos creados con exito');
    }

    protected function addMigrations1()
    {
        $this->comment('Ejecutando migraciones parte 1');

        Artisan::call('migrate', ['--path' => '/database/migrations/2024_02_15_143601_create_product_tax_rate_table.php', '--force' => true ]);
        Artisan::call('migrate', ['--path' => '/database/migrations/2024_03_05_110017_add_has_percentage_to_tax_rates_table.php', '--force' => true]);
    }

    protected function exportTaxProducts()
    {
        $this->comment('exportando impuestos de la tabla products a la tabla product_tax_rates');

        $products = Product::all();

        $bar = $this->output->createProgressBar($products->count());

        $bar->start();

        foreach ($products as $product) {

            $product->taxRates()->attach([
                $product->tax_rate_id => [
                    'value' => 0,
                ],
            ]);

            $bar->advance();
        }

        $this->comment('Importacion de impuestos realizada con exito');
    }

    protected function addMigrations2()
    {
        $this->comment('Ejecutando migraciones parte 2');

        Artisan::call('migrate', ['--path' => '/database/migrations/2024_03_08_150548_remove_column_tax_rate_id_to_products_table.php', '--force' => true]);
        Artisan::call('migrate', ['--path' => '/database/migrations/2024_03_06_142403_create_document_taxes_table.php', '--force' => true]);
        Artisan::call('migrate', ['--path' => '/database/migrations/2024_03_06_142417_create_document_tax_rates_table.php', '--force' => true]);

        $this->comment('Migraciones parte 2 ejecutadas con exito');
    }

    protected function exportDetailBill()
    {
        $this->comment('Exportando detalle de facturas');

        $details = DetailBill::all();

        $bar = $this->output->createProgressBar($details->count());

        $bar->start();

        foreach ($details as $detail) {
            $withholdingTax = $detail->documentTaxes()->create([
                'tribute_name' => $detail->tribute,
                'tax_amount' => $detail->tax,
            ]);

            DocumentTaxRate::create([
                'has_percentage' => 1,
                'rate' => $detail->rate,
                'taxable_amount' => rounded($detail->total - $detail->tax),
                'tax_amount' => $detail->tax,
                'document_tax_id' => $withholdingTax->id,
            ]);

            $bar->advance();
        }

        $this->comment('Detalle de facturas exportadas con exito');
    }

    protected function exportBills()
    {
        $this->comment('Exportando facturas');

        $bills = Bill::all();

        $bar = $this->output->createProgressBar($bills->count());

        $bar->start();

        foreach ($bills as $bill) {
            DocumentTaxService::calcTaxRatesForDocument($bill);
            $bar->advance();
        }

        $this->comment('facturas exportadas con exito');
    }

    protected function addMigreation3()
    {
        $this->comment('Ejecutando migraciones parte 3');

        Artisan::call('migrate', ['--path' => '/database/migrations/2024_03_12_163248_remove_column_inc_iva_to_bills_table.php', '--force' => true]);
        Artisan::call('migrate', ['--path' => '/database/migrations/2024_03_12_164252_remove_column_rate_tribute_tax_to_detail_bills_table.php', '--force' => true]);

        $this->comment('Migraciones parte 3 ejecutadas con exito');
    }
}

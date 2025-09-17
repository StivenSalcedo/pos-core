<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder {

    public function run() {

        Artisan::call('storage:link');

        Storage::deleteDirectory('public/images/logos');
        Storage::makeDirectory('public/images/logos');

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TributeSeeder::class);
        $this->call(TaxRateSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(IdentificationDocumentSeeder::class);


        Customer::create([
            'identification_document_id' => 3,
            'no_identification' => '222222222222',
            'names' => 'Consumidor final',
            'top' => '0',
        ]);

        if (App::isLocal()) {
            if (true) {
                $this->call(CustomerSeeder::class);
                $this->call(ProviderSeeder::class);
                $this->call(CategorySeeder::class);
                $this->call(ProductSeeder::class);
                $this->call(StaffSeeder::class);
                $this->call(PayrollSeeder::class);
                /* $this->call(OutputSeeder::class); */
            }
        }

        $this->call(NumberingRangeSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(TerminalSeeder::class);
        $this->call(FactusConfigurationSeeder::class);

        if(App::environment('production')){
            shell_exec('chmod -R 777 ' . storage_path('app/public/'));
        }
    }
}

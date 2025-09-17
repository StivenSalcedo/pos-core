<?php

namespace App\Console\Commands;

use App\Models\Tribute;
use Database\Seeders\IdentificationDocumentSeeder;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ElectronicPOSStep1Command extends Command
{
    protected $signature = 'pos:add-step1';

    protected $description = 'Actulaiza el modulo de clientes y de impuestos para adaptarlo al los requisitos del POS electronico';

    public function handle()
    {
        $this->comment('Iniciando ejecucion');

        Artisan::call('migrate', ['--path' => '/database/migrations/2024_04_19_152729_create_identification_documents_table.php', '--force' => true]);
        Artisan::call('db:seed', ['--class' => IdentificationDocumentSeeder::class, '--force' => true]);
        Artisan::call('migrate', ['--path' => '/database/migrations/2024_04_19_155433_add_identification_document_id_to_customers_table.php', '--force' => true]);
        Artisan::call('migrate', ['--path' => '/database/migrations/2024_04_22_103953_add_tribute_to_customers_table.php', '--force' => true]);
        Artisan::call('migrate', ['--path' => '/database/migrations/2024_04_22_150426_add_legal_organization_to_customers_table.php', '--force' => true]);
        Artisan::call('migrate', ['--path' => '/database/migrations/2024_04_23_085153_add_api_tribute_id_to_tributes_table.php', '--force' => true]);

        $this->addTributeId();

        $this->comment('Ejecucion finalizada');
    }

    private function addTributeId()
    {
        $tributes = Tribute::all();

        foreach ($tributes as $value) {

            switch ($value->id) {
                case 1:
                    $value->api_tribute_id = 1;
                    break;
                case 2:
                    $value->api_tribute_id = 4;
                    break;
                case 3:
                    // TODO pendiente de actualizar
                    $value->api_tribute_id = 0;
                    break;
                case 4:
                    // TODO pendiente de actualizar
                    $value->api_tribute_id = 0;
                    break;

                default:
                    throw new Exception('No se encontró el id correspondiente al tributo');
                    break;
            }

            $value->save();
        }
    }
}

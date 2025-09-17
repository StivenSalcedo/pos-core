<?php

namespace App\Console\Commands;

use App\Models\FactusConfiguration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class FactusConfigurationCommand extends Command
{
    protected $signature = 'factus:configuration';

    protected $description = 'Crea las configuraciones de Factus en la base de datos.';

    public function handle()
    {
        $this->info('Inicio de ejecucion de comando');

        $this->info('Creando tabla de configuraciones de Factus...');

        $this->call('migrate', [
            '--path' => 'database/migrations/2024_07_01_204926_create_factus_configurations_table.php',
            '--force' => true
        ]);

        $url = App::environment('production') ? 'https://api.factus.com.co/' : 'http://api.test/';

        $this->call('config:cache');

        $configurations = [
            'url' => $url,
            'client_id' => config('services.factus.client'),
            'client_secret' => config('services.factus.secret'),
            'email' => config('services.factus.email'),
            'password' => config('services.factus.password')
        ];

        $this->info('Creando configuraciones de Factus...');

        FactusConfiguration::create([
            'is_api_enabled' => factusIsEnabled(),
            'api' => $configurations
        ]);

        Cache::forget('api_configuration');
        Cache::forget('is_api_enabled');

        $this->info('Configuraciones de Factus creadas exitosamente.');
    }
}

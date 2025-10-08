<?php

namespace Database\Seeders;

use App\Models\FactusConfiguration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class FactusConfigurationSeeder extends Seeder
{
    public function run()
    {
        $url = App::environment('production') ? 'https://api.factus.com.co/' : 'https://api-sandbox.factus.com.co/';

        $configurations = [
            'url' => $url,
            'client_id' => '9fce79f0-5b7e-4415-b59c-509599d0df1d',
            'client_secret' => 'QP1LCJjVIUEiipIh4uewQoQJ2lEu0XEQ2OsIUhJc',
            'email' => 'sandbox@factus.com.co',
            'password' => 'sandbox2024%'
        ];

        FactusConfiguration::create([
            'is_api_enabled' => true,
            'api' => $configurations
        ]);
    }
}

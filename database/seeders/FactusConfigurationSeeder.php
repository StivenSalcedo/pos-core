<?php

namespace Database\Seeders;

use App\Models\FactusConfiguration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class FactusConfigurationSeeder extends Seeder
{
    public function run()
    {
        $url = App::environment('production') ? 'https://api.factus.com.co/' : 'http://api.test/';

        $configurations = [
            'url' => $url,
            'client_id' => '',
            'client_secret' => '',
            'email' => '',
            'password' => ''
        ];

        FactusConfiguration::create([
            'is_api_enabled' => false,
            'api' => $configurations
        ]);
    }
}

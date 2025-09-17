<?php

namespace App\Services\Factus;

use App\Exceptions\CustomException;
use App\Models\FactusConfiguration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class AppService
{
    public static function getTokenFactus(string $redirectUrl): array
    {
        $domain = (config('app.app_factus_url') ?? (App::isLocal() ? 'http://app.test' : 'https://app.factus.com.co'));

        $config = FactusConfiguration::first()->api;

        $data = [
            'email' => $config['email'],
            'password' => $config['password'],
        ];

        $response = Http::acceptJson()->post("{$domain}/api/external-authentication", $data);

        if($response->status() !== 200) {
            throw new CustomException("Ha ocurrido un error inesperado al abrir Factus", 1);
        }

        $token = $response->json()['token'];

        return [
            'token' => $token,
            'domain' => $domain,
            'redirect_url' => $redirectUrl
        ];
    }
}

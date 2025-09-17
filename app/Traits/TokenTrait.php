<?php

namespace App\Traits;

use App\Models\AccessToken;
use App\Services\FactusConfigurationService;
use Exception;
use Illuminate\Support\Facades\Http;

trait TokenTrait
{
    public static function getAccessToken()
    {
        $apiConfiguration = FactusConfigurationService::apiConfiguration();

        $data = [
            'grant_type' => 'password',
            'client_id' => $apiConfiguration['client_id'],
            'client_secret' => $apiConfiguration['client_secret'],
            'username' => $apiConfiguration['email'],
            'password' => $apiConfiguration['password'],
        ];

        $response = Http::acceptJson()->post($apiConfiguration['url'].'oauth/token', $data);

        $access_token = $response->json();

        if ($response->status() !== 200) {
            if (array_key_exists('error', $access_token) && $access_token['error'] === 'invalid_client') {
                throw new Exception('Error al autenticarse con la API');
            } else {
                throw new Exception('Error al obtener el token de acceso');
            }
        }

        AccessToken::whereNotNull('id')->delete();

        $access_token = AccessToken::create([
            'access_token' => $access_token['access_token'],
            'refresh_token' => $access_token['refresh_token'],
            'expires_at' => now()->addSecond($access_token['expires_in']),
        ]);

        return $access_token;
    }
}

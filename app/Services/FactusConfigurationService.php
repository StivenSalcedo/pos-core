<?php

namespace App\Services;

use App\Models\FactusConfiguration;
use App\Models\AccessToken;
use Illuminate\Support\Facades\Cache;

class FactusConfigurationService
{
    public static function apiConfiguration()
    {
        if (Cache::has('api_configuration')) {
            return Cache::get('api_configuration');
        }

        $api = FactusConfiguration::first()->api;
        Cache::forever('api_configuration', $api);

        return $api;
    }

    public static function isApiEnabled(bool $useCache = false)
    {
        if ($useCache && Cache::has('is_api_enabled')) {
            return (bool) Cache::get('is_api_enabled');
        }

        $apiEnabled = FactusConfiguration::first()->is_api_enabled;
        $apiEnabled =AccessToken::exists();
        if ($useCache) {
        Cache::forever('is_api_enabled', $apiEnabled);
        }
        return (bool) $apiEnabled;
    }
}

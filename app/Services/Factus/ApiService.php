<?php

namespace App\Services\Factus;

use Exception;
use Illuminate\Support\Facades\Cache;

class ApiService
{
    public static function numberingRanges()
    {
        $filters = [
            'filter' => [
                'document' => '21',
                'is_active' => '1',
            ],
        ];

        $response = HttpService::apiHttp()->get('numbering-ranges', $filters);

        if ($response->status() !== 200) {
            throw new Exception('Ha ocurrido un error inesperado al consultar los rangos de numeración');
        }

        return $response->json()['data'];
    }

    public static function payrollIsEnabled()
    {
        $isEnabled = Cache::get('payroll_is_enabled', false);

        if (! $isEnabled) {
            $response = HttpService::apiHttp()->get('environments/26/status');

            if ($response->status() !== 200) {
                throw new Exception('Ha ocurrido un error inesperado al consultar los rangos de numeración');
            }

            $response = $response->json();

            $isEnabled = $response['data']['enabled'];

            Cache::forever('payroll_is_enabled', $isEnabled);

        }

        return $isEnabled;
    }

    public static function companyData()
    {
        $response = HttpService::apiHttp()->get('company');

        if ($response->status() !== 200) {
            throw new Exception('Ha ocurrido un error inesperado al consultar los datos de la empresa');
        }

        return $response->json()['data'];
    }
}

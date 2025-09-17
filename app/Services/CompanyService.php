<?php

namespace App\Services;

use App\Services\Factus\ApiService;

class CompanyService
{
    protected static $companyPosNitWhiteList = [
        'xxxxxx'
    ];

    public static function companyData(): array
    {
        if (FactusConfigurationService::isApiEnabled()) {
            $companyData = ApiService::companyData();

            $company = [
                'nit' => $companyData['nit'] . '-' . $companyData['dv'],
                'name' => $companyData['graphic_representation_name'],
                'direction' => (in_array($companyData['nit'], self::$companyPosNitWhiteList)? session('config')->direction : $companyData['address']),
                'phone' => $companyData['phone'],
            ];
        } else {

            $company = [
                'name' => session('config')->name,
                'nit' => session('config')->nit,
                'direction' => session('config')->direction,
                'phone' => session('config')->phone,
            ];
        }

        return $company;
    }
}

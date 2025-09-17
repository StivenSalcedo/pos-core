<?php

namespace App\Exports;

use App\Models\Provider;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProvidersExport implements FromQuery, WithMapping, WithHeadings {
    
    use Exportable;

    public function query() {
        return Provider::query();
    }

    public function headings(): array {
        return [
            'N° Identificación',
            'Nombres',
            'Celular',
            'Dirección',
            'Regimen juridico',
            'Descripción',
            'Estado',
        ];
    }

    public function map($provider): array {

        return [
            [
                $provider->no_identification,
                $provider->name,
                $provider->phone,
                $provider->direction,
                $provider->type->getLabel(),
                $provider->description,
                $provider->status === '0' ? 'Activo' : 'Inactivo',
            ],
        ];
    }
}

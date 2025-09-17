<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromQuery, WithMapping, WithHeadings {

    use Exportable;

    public function query() {
        return Customer::query();
    }

    public function headings(): array {
        return [
            'N° Identificación',
            'Nombres',
            'Dirección',
            'Celular',
            'Email'
        ];
    }

    public function map($customer): array {

        return [
            [
                $customer->no_identification,
                $customer->names,
                $customer->direction,
                $customer->phone,
                $customer->email,
            ],
        ];
    }
}

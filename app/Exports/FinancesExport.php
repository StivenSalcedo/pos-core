<?php

namespace App\Exports;

use App\Models\Finance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancesExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping
{

    public $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Identificación',
            'Cliente',
            'Telefono',
            'Email',
            'N° factura',
            'Fecha de vencimiento',
            'Dias restantes',
            'N° Pagos',
            'Saldo',
            'Pagado',
            'Total',
            'Estado',
        ];
    }

    public function map($finance): array
    {

        return [
            [
                $finance->bill->customer->no_identification,
                $finance->bill->customer->names,
                $finance->bill->customer->phone,
                $finance->bill->customer->email,
                $finance->bill->number,
                $finance->created_at->format('d-m-Y h:i'),
                getDays($finance->created_at, $finance->due_date, false),
                $finance->details_count,
                '$ ' . number_format($finance->pending, 0, '.', ','),
                '$ ' . number_format($finance->paid, 0, '.', ','),
                '$ ' . number_format($finance->bill->total, 0, '.', ','),
                $finance->status ? 'pendientes' : 'pagada',
            ],
        ];
    }
}

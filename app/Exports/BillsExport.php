<?php

namespace App\Exports;

use App\Models\Bill;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BillsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{

    use Exportable;

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
            'NIT/Cédula',
            'Cliente',
            'Teléfono',
            'Email',
            'Fecha',
            'N° Factura',
            'Terminal',
            'Servicio voluntario',
            'Subtotal',
            'Descuento',
            'IVA',
            'INC',
            'Total',
            'Medio de pago',
            'Estado',
            'Observaciones'
        ];
    }

    public function map($bill): array
    {
        return [
            [
                $bill->customer->no_identification,
                $bill->customer->names,
                $bill->customer->phone,
                $bill->customer->email,
                $bill->created_at->format('d-m-Y h:i'),
                $bill->number,
                $bill->terminal->name,
                '$ ' . number_format($bill->tip, 0, '.', ','),
                '$ ' . number_format($bill->subtotal, 0, '.', ','),
                '$ ' . number_format($bill->discount, 0, '.', ','),
                '$ ' . number_format($bill->iva, 0, '.', ','),
                '$ ' . number_format($bill->inc, 0, '.', ','),
                '$ ' . number_format($bill->total, 0, '.', ','),
                $bill->paymentMethod->name,
                $bill->status ? 'anulada' : 'activa',
                $bill->observation,
            ],
        ];
    }
}

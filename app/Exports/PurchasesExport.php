<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchasesExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize {

    use Exportable;

    public $query;

    public function __construct($query) {
        $this->query = $query;
    }

    public function query() {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'N° Compra',
            'Fecha',
            'NIT/Cédula',
            'Proveedor',
            'Dirección',
            'Teléfono',
            'Total',
            'Estado',
        ];
    }

    public function map($purchase): array {

        return [
            [
                $purchase->id,
                $purchase->created_at->format('d-m-Y h:i'),
                $purchase->provider->no_identification,
                $purchase->provider->name,
                $purchase->provider->direction,
                $purchase->provider->phone,
                '$ ' . number_format($purchase->total, 0, '.', ','),
                $purchase->status ? 'anulada' : 'activa',
            ],
        ];
    }
}

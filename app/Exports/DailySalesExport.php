<?php

namespace App\Exports;

use App\Models\DailySale;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DailySalesExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection(): Collection
    {
        $collection = $this->query->get();
        $model = new DailySale;
        $model->fill([
            'format_creating_date' => '',
            'from' => '',
            'to' => '',
            'subtotal_amount' => $collection->sum('subtotal_amount'),
            'discount_amount' => $collection->sum('discount_amount'),
            'iva_amount' => $collection->sum('iva_amount'),
            'inc_amount' => $collection->sum('inc_amount'),
            'total_amount' => $collection->sum('total_amount'),
        ]);

        $collection->push($model);
        return $collection;

    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Desde',
            'Hasta',
            'Subtotal',
            'Descuento',
            'IVA',
            'INC',
            'Total',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->format_creating_date,
            $sale->from,
            $sale->to,
            $sale->subtotal_amount,
            $sale->discount_amount,
            $sale->iva_amount,
            $sale->inc_amount,
            $sale->total_amount,
        ];
    }
}

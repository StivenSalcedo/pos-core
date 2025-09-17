<?php

namespace App\Exports\Sheets;

use App\Models\Presentation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductsDetailSheet implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithTitle{

    public function title(): string {
        return 'Presentaciones';
    }

    public function query() {
        return Presentation::query();
    }
    

    public function headings(): array {
        return [
            'Codigo de barras',
            'Referencia',
            'Producto',
            'Nombre',
            'Precio',
            'Cantidad',
        ];
    }

    public function map($presentation): array {

        return [
            [
                $presentation->product->barcode,
                $presentation->product->reference,
                $presentation->product->name,
                $presentation->name,
                $presentation->quantity,
            ],
        ];
    }

    public function columnFormats(): array {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_NUMBER,
        ];
    }

}
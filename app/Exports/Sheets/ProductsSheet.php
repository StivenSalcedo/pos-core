<?php

namespace App\Exports\Sheets;

use App\Exports\Sheets\ProductsDetailSheet;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
class ProductsSheet implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithTitle {

    use Exportable;
    public function __construct(public $products) {}
    public function title(): string {
        return 'Productos';
    }

     public function collection()
    {
        return $this->products;
    }

    public function headings(): array {
        return [
            'Categoria',
            'Código de barras',
            'Referencia',
            'Nombre',
            'Impuestos',
            'Costo',
            'Precio',
            'Stock',
            'Cantidad',
            'Unidades',
            'Tiene presentaciones',
            'Destacado',
            'Estado',
        ];
    }

    public function map($product): array {

        return [
            [
                $product->category->name ?? 'Sin categoria',
                $product->barcode,
                $product->reference,
                $product->name,
                $product->taxRates->transform(fn($taxRate) => $taxRate->format_rate)->implode(', '),
                $product->cost,
                $product->price,
                $product->stock,
                $product->quantity,
                $product->units,
                $product->has_presentations === '0' ? 'SI' : 'NO',
                $product->top === '0' ? 'SI' : 'NO',
                $product->status === '0' ? 'Activo' : 'Inactivo',
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

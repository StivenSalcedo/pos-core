<?php

namespace App\Exports;

use App\Exports\Sheets\ProductsDetailSheet;
use App\Exports\Sheets\ProductsSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductsExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(public $products) {}

    public function sheets(): array
    {
        return [
            new ProductsSheet($this->products)
          
        ];
    }
}

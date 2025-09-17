<?php

namespace App\Exports;

use App\Exports\Sheets\ProductsDetailSheet;
use App\Exports\Sheets\ProductsSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductsExport implements WithMultipleSheets {
    
    use Exportable;

    public function sheets(): array {
        
        $sheets = [];

        $sheets[] = new ProductsSheet();
        $sheets[] = new ProductsDetailSheet();

        return $sheets;
    }
    
}

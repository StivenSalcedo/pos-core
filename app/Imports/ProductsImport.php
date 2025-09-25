<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use App\Models\Terminal;
use App\Models\Category;

class ProductsImport implements OnEachRow, WithHeadingRow, SkipsEmptyRows
{
    private Collection $taxRates;

    public function __construct()
    {
        $this->taxRates = TaxRate::enabled()->get();
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $data = [
            'barcode' => trim($row['codigo_de_barras']),
            'reference' => trim($row['referencia']),
            'name' => trim($row['nombre']),
            'stock' => $row['cantidad'],
            'cost' => $row['costo'],
            'price' => $row['precio'],
            'quantity' => 0,
            'units' => 0,
            'top' => Product::TOP_INACTIVE,
            'status' => Product::ACTIVE,
            'has_presentations' => Product::HAS_PRESENTATION_INACTIVE,
            'tax_rate_id' => $row['impuesto'],
            'terminal_id' => Terminal::min('id'),
            'category_id' =>Category::min('id') ?? null,
        ];

        $this->validateModel($data);

        //$value = $this->calculateTaxValue($data['ml'], $data['tax_rate_id']);

        $product = Product::create($data);

        $product->taxRates()->attach([$data['tax_rate_id'] => ['value' => 0]]);
    }

    protected function validateModel(array $row): void
    {
        $rules = [
            'barcode' => 'required|string|unique:products',
            'reference' => 'required|string|unique:products',
            'name' => 'required|string|min:3|max:250',
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'cost' => 'required|integer|min:0|max:99999999',
            'price' => 'required|integer|min:0|max:99999999',
            'stock' => 'required|integer|min:0|max:99999999',
           // 'ml' => 'required_if:tax_rate_id,6,7|integer|max:999999999',
            'terminal_id' => 'required|integer',
        ];

        Validator::make($row, $rules)->validate();
    }

    
}

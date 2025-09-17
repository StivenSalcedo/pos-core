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
            'ml' => empty($row['mililitros']) ? 0 : $row['mililitros'],
        ];

        $this->validateModel($data);

        $value = $this->calculateTaxValue($data['ml'], $data['tax_rate_id']);

        $product = Product::create($data);

        $product->taxRates()->attach([$data['tax_rate_id'] => ['value' => $value]]);
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
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'ml' => 'required_if:tax_rate_id,6,7|integer|max:999999999',
        ];

        Validator::make($row, $rules)->validate();
    }

    protected function calculateTaxValue(int $ml, int $tax_rate_id)
    {
        if ($tax_rate_id === 6 || $tax_rate_id === 7) {

            $rate = $this->taxRates->find($tax_rate_id)->rate;

            return (int)($ml / 100) * $rate;
        }

        return 0;
    }
}

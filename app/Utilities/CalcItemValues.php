<?php

namespace App\Utilities;

use App\Models\Product;
use Illuminate\Support\Collection;

class CalcItemValues
{
    public static function taxes(Collection $items): Collection
    {
        foreach ($items as $key => $item) {

            $item['tax_rates']  = Product::find($item['id'])->taxRates->map(function ($taxRate) {
                $data = $taxRate->only('id', 'has_percentage', 'rate', 'tribute_name');
                $data['value'] = $taxRate['pivot']['value'];

                return $data;
            })->toArray();

            $grossValue = self::getGrossValue($item);
            $item['gross_value'] = $grossValue;


            foreach ($item['tax_rates'] as $key2 => $value) {
                $value['taxable_amount'] = $grossValue;

                if ($value['has_percentage']) {
                    $value['tax_amount'] = rounded(($grossValue * floatval($value['rate'])) / 100);
                } else {
                    $value['tax_amount'] = $value['value'] * $item['amount'];
                }

                $item['tax_rates'][$key2] = $value;
                $items[$key] = $item;
            }
        }

        return collect($items);
    }

    public static function getGrossValue($product)
    {
        $total = ($product['price'] * $product['amount']) - $product['discount'];

        $sumPercentTax = collect($product['tax_rates'])
            ->filter(fn ($item) => (int) $item['has_percentage'])
            ->map(fn ($item) => ['rate' => $item['rate'] / 100])
            ->sum('rate');

        $sumValueTax = collect($product['tax_rates'])
            ->filter(fn ($item) => !(int) $item['has_percentage'])
            ->map(fn ($item) => ['value' => $item['value']])
            ->sum('value');

        $rate = $sumPercentTax + 1;

        $sumValueTax = $sumValueTax * $product['amount'];

        return rounded(($total - $sumValueTax) / $rate, 0);
    }
}

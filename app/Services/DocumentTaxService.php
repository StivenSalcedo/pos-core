<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\DetailBill;
use App\Models\DocumentTaxRate;
use App\Models\Tribute;

class DocumentTaxService
{
    public static function calcTaxRatesForItems(DetailBill $detail, array $taxes)
    {
        $tributes = Tribute::enabled()->get();

        foreach ($tributes as $tribute) {

            //se crea una collecion de los items separados por el tributo
            $itemsByTribute = collect($taxes)->whereIn('tribute_name', $tribute->name);

            if ($itemsByTribute->count()) {

                $tax = $detail->documentTaxes()->create([
                    'tribute_name' => $tribute->name,
                    'tax_amount' => rounded($itemsByTribute->sum('tax_amount')),
                ]);

                //se crea una collecion de los items separados por el el porcentaje de impuesto
                $itemsByRates = $itemsByTribute->filter(fn ($item) => (int) $item['has_percentage'])->groupBy('rate');

                foreach ($itemsByRates as $key => $itemRate) {

                    DocumentTaxRate::create([
                        'has_percentage' => 1,
                        'rate' => $key,
                        'taxable_amount' => $itemRate->first()['taxable_amount'],
                        'tax_amount' => rounded($itemRate->sum('tax_amount')),
                        'document_tax_id' => $tax->id,
                    ]);
                }

                //se crea una collecion de los items separados por peso de impuesto
                $itemsByRates = $itemsByTribute->filter(fn ($item) => (int) $item['has_percentage'] === 0)->groupBy('rate');

                foreach ($itemsByRates as $key => $itemRate) {

                    DocumentTaxRate::create([
                        'has_percentage' => 0,
                        'rate' => $key,
                        'taxable_amount' => $itemRate->first()['taxable_amount'],
                        'tax_amount' => rounded($itemRate->sum('tax_amount')),
                        'document_tax_id' => $tax->id,
                    ]);
                }
            }
        }
    }

    public static function calcTaxRatesForDocument(Bill $document)
    {
        $taxes = $document->details()
            ->with('documentTaxes')
            ->get()
            ->flatMap(function ($detail) {
                return $detail->documentTaxes;
            });

        $taxesByTribute = $taxes->groupBy('tribute_name');

        foreach ($taxesByTribute as $key => $item) {

            $tributes = Tribute::enabled()->get();

            $tribute = $tributes->where('name', $key)->first();

            $tax = $document->documentTaxes()->create([
                'tribute_name' => $tribute->name,
                'tax_amount' => rounded($item->sum('tax_amount')),
            ]);

            $documentTaxRate = DocumentTaxRate::whereIn('document_tax_id', $item->pluck('id'))->get();

            $documentTaxRateByRate = $documentTaxRate->filter(fn ($item) => $item->has_percentage)->groupBy('rate');

            foreach ($documentTaxRateByRate as $key => $itemRate) {

                DocumentTaxRate::create([
                    'has_percentage' => 1,
                    'rate' => $key,
                    'taxable_amount' => rounded($itemRate->sum('taxable_amount')),
                    'tax_amount' => rounded($itemRate->sum('tax_amount')),
                    'document_tax_id' => $tax->id,
                ]);
            }

            $documentTaxRateByRate = $documentTaxRate->filter(fn ($item) => $item->has_percentage === 0)->groupBy('rate');

            foreach ($documentTaxRateByRate as $key => $itemRate) {

                DocumentTaxRate::create([
                    'has_percentage' => 0,
                    'rate' => $key,
                    'taxable_amount' => rounded($itemRate->sum('taxable_amount')),
                    'tax_amount' => rounded($itemRate->sum('tax_amount')),
                    'document_tax_id' => $tax->id,
                ]);
            }
        }
    }
}

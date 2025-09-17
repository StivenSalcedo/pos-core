<?php

namespace App\Console\Commands;

use App\Models\Bill;
use App\Models\DetailBill;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class UpdateSales extends Command {
    
    protected $signature = 'update:sales';

    protected $description = 'Calcula las ventas desde la primera venta que se hizo';

    public function handle() {
        
        Sale::truncate();

        $startDate = DetailBill::orderBy('created_at', 'ASC')->first()->created_at;

        while ($startDate->lt(now())) {

            $this->info('Dia ' . $startDate->format('d-m-Y'));

            $collection = DetailBill::whereDate('created_at', $startDate)->whereRelation('bill', 'status', Bill::ACTIVA)->get();

            $collect = $this->calcTotales($collection);

            if ($collect->count()) {

                foreach ($collect as $key => $item) {
                    
                    $quantity = $item->sum('amount');
                    $units = 0;
                    $product = Product::find($key);

                    if (!intval($product->has_presentations)) {
                        $units = $item->sum('units') - (intval($item->sum('units') / $product->quantity) * $product->quantity );
                        $quantity = $quantity + intval($item->sum('units') / $product->quantity);
                    }

                    Sale::create([
                        'quantity' => $quantity,
                        'units' => $units,
                        'total' => $item->sum('total'),
                        'product_id' => $key,
                        'created_at' => $startDate,
                    ]);

                }

            }

            $startDate->addDay();

        }

    }

    protected function calcTotales(Collection $collection) : SupportCollection{

        $collect = collect();

        foreach ($collection as $item) {

            $array = [];

            $array['product_id'] = $item->product_id;

            if ($item->presentation) {

                $array['units'] = $item->presentation->quantity * $item->amount;
                $array['amount'] = 0;

            }else{

                $array['units'] = 0;
                $array['amount'] = $item->amount;

            }

            $array['total'] = $item->total;

            $collect->push($array);

            
        }

        return $collect->groupBy('product_id');

    }
}

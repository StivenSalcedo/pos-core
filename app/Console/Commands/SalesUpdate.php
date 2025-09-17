<?php

namespace App\Console\Commands;

use App\Models\Bill;
use App\Models\DetailBill;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Storage;

class SalesUpdate extends Command {
    
    protected $signature = 'sales:update {--today}';

    protected $description = 'Calcula las ventas del dia anterior';

    public function handle() {

        $date = $this->option('today') ? now() : now()->subDay();

        Sale::whereDate('created_at', $date)->delete();

        $collection = DetailBill::whereDate('created_at', $date)->whereRelation('bill', 'status', Bill::ACTIVA)->get();

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
                    'created_at' => $date,
                ]);

            }

        }

        Storage::disk('root')->append('logs/tareas.log', '[' . now()->format('d-m-Y h:i:s a') . '] Se actualizo la tabla sales con éxito');

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

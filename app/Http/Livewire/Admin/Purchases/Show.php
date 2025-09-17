<?php

namespace App\Http\Livewire\Admin\Purchases;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component {

    public $purchase;

    public function mount(Purchase $purchase){
        $this->purchase = $purchase;
    }

    public function render() {
        return view('livewire.admin.purchases.show');
    }

    public function cancelPurchase(){

        if (Purchase::find($this->purchase->id)->status === '1'){
            $this->purchase->refresh();
            return $this->emit('alert', 'La compra esta anulada');
        }

        $details = $this->purchase->details;

        try {

            DB::beginTransaction();

            $this->purchase->status = '1';
            $this->purchase->save();

            foreach ($details as $value) {
                
                $product = Product::find($value->product_id);
                
                if (!intval($product->has_presentations)) {
                    $units = $product->units - (($value->amount * $product->quantity) + $value->units);
                    Product::where('id', $value->product_id)->update([
                        'units' => $units,
                        'stock' => (int) ($units / $product->quantity),
                    ]);
                }else{
                    Product::where('id', $value->product_id)->decrement('stock', $value->amount);
                }
                
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->emit('error', 'Oops... Ha ocurrido un error inesperado. Vuelve a intentarlo');
        }

        return $this->emit('success', 'Compra anulada con éxito');

    }

}

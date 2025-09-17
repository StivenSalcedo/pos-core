<?php

namespace App\Http\Livewire\Admin\Purchases;

use App\Exports\PurchasesExport;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component {

    use WithPagination;

    // TODO Actualizar la funcion de exportar a excel

    protected $listeners = ['render'];

    public $search, $filter='1', $status='0', $filterDate='0', $startDate, $endDate;

    public $filters = [
        1 => 'N° Compra',
        2 => 'Nombre proveedor'
    ];

    public function render() {
        $filter = [1 => 'id',  2 => 'name'][$this->filter];

        $total = Purchase::query()->search($filter, $this->search)
                        ->status($this->status)
                        ->date($this->filterDate, $this->startDate, $this->endDate)
                        ->sum('total');

        $purchases = Purchase::query()->search($filter, $this->search)
                        ->status($this->status)
                        ->date($this->filterDate, $this->startDate, $this->endDate)
                        ->latest()
                        ->paginate(10);

        return view('livewire.admin.purchases.index', compact('purchases', 'total'))->layoutData(['title' => 'Compras']);
    }

    public function updatedSearch(){
        $this->resetPage();
    }

    public function updatedFilter(){
        $this->resetPage();
    }

    public function cancelPurchase(Purchase $purchase){

        if ($purchase->status === '1') return $this->emit('alert', 'La compra esta anulada');

        $details = $purchase->details;

        try {

            DB::beginTransaction();

            $purchase->status = '1';
            $purchase->save();

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

    public function exportPurchase(){

        $filter = [1 => 'id',  2 => 'names'][$this->filter];

        $query = Purchase::query()->search($filter, $this->search)
                        ->status($this->status)
                        ->date($this->filterDate, $this->startDate, $this->endDate)
                        ->latest();

        return Excel::download(new PurchasesExport($query), 'Compras.xlsx');
    }
}

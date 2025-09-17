<?php

namespace App\Http\Livewire\Admin\Sales;

use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component {

    use WithPagination;

    public $search, $filterDate='8', $startDate, $endDate, $productsArray, $productsSelected=[], $useBarcode;

    public $total=0;

    public function mount(){

        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');

        $this->useBarcode = intval(Company::first()->barcode);

        $this->productsArray = Product::select(['id', 'reference', 'name'])
                        ->where('status', '0')
                        ->get()
                        ->transform(function($item){
                            return [
                                'id' => $item->id,
                                'reference' => $item->reference,
                                'name' => $item->name,
                            ];
                        })
                        ->toArray();

    }

    public function render() {

        $products = Sale::with('product')
            ->whereHas('product', function ($query) {
                $query
                    ->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('reference', 'LIKE', "%{$this->search}%");
            })
            ->selectRaw('MAX(product_id) AS product_id, SUM(quantity) AS quantity, SUM(units) AS units, SUM(total) AS total')
            ->date($this->filterDate, $this->startDate, $this->endDate)
            ->searchByIds($this->productsSelected)
            ->groupBy('product_id')
            ->paginate(10);

        $this->total = Sale::date($this->filterDate, $this->startDate, $this->endDate)
            ->whereHas('product', function ($query) {
                $query
                    ->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('reference', 'LIKE', "%{$this->search}%");
                })
            ->sum('total');

        return view('livewire.admin.sales.index', compact('products'));

    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function updatedProductsSelected(){
        $this->resetPage();
    }

    public function updatedStartDate(){
        $this->resetPage();
    }

    public function updatedEndDate(){
        $this->resetPage();
    }

    public function getToday(){
        Artisan::call('sales:update --today');
    }
}

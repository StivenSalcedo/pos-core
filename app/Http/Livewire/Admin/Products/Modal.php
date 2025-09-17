<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Modal extends Component {

    use WithPagination;

    protected $listeners = ['openModal'];

    public $openModal=false, $search, $filter='2';

    public $filters = [
        1 => 'Referencia',
        2 => 'Nombre'
    ];

    public function render() {

        $filter = [1 => 'reference',  2 => 'name'][$this->filter];

        $products = Product::where($filter, 'LIKE', '%' . $this->search . '%')
                    ->where('status', '0')
                    ->where('has_inventory', '0')
                    ->take(11)
                    ->latest()
                    ->get();

        return view('livewire.admin.products.modal', compact('products'));
    }

    public function openModal(){
        $this->reset();
        $this->openModal = true;
    }

    public function updatedSearch(){
        $this->resetPage();
    }

    public function updatedFilter(){
        $this->resetPage();
    }

    public function selected(Product $product){
        $this->emitTo('admin.purchases.create', 'setProduct', $product->id);
        $this->emitTo('admin.bills.create', 'setProduct', $product->id);
        $this->reset();
    }
}

<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Company;
use App\Models\Presentation;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ModalAlpine extends Component {

    use WithPagination;

    public $products, $presentations, $barcode;

     public function render() {

        $this->barcode = Company::first()->barcode;

        $this->products = Product::select(['id', 'reference', 'name', 'stock', 'price'])
                        ->where('status', '0')
                        ->where('terminal_id',  auth()->user()->terminals->first()->id)
                        ->orderBy('top', 'ASC')
                        ->get()
                        ->toArray();

        $this->presentations = Presentation::select('id', 'name', 'quantity', 'product_id')->where('status', '0')->get()->toArray();

        return view('livewire.admin.products.modal-alpine');
    }
}

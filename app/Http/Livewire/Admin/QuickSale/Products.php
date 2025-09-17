<?php

namespace App\Http\Livewire\Admin\QuickSale;

use App\Models\Category;
use App\Models\Presentation;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Products extends Component
{
    protected $listeners = ['render' => 'refreshProducts'];

    public $products, $presentations, $categories;

    public $openModal = false;

    public function mount()
    {
        $this->refreshProducts();
    }

    public function render()
    {
        return view('livewire.admin.quick-sale.products');
    }

    public function refreshProducts()
    {
        $this->categories = Category::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $this->products = Product::select(['id', 'reference', 'category_id', 'name', 'price', DB::raw('CASE WHEN stock > 0 || units > 0 || has_inventory = "1" THEN 1 ELSE 0 END AS has_stock')])
            ->where('status', '0')
            ->orderBy('top', 'ASC')
            ->orderBy('name', 'ASC')
            ->get()
            ->toArray();

        $this->presentations = Presentation::select('id', 'name', 'price', 'product_id')
            ->where('status', '0')
            ->get()
            ->toArray();
    }
}

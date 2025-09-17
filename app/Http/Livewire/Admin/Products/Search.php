<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Company;
use App\Models\Presentation;
use App\Models\Product;
use Livewire\Component;

class Search extends Component
{
    // TODO cambiar el estado de la pistola sin hacer peticiones al backend
    protected $listeners = ['getProducts'];

    public $productsArray;

    public $presentations;

    public $useBarcode;

    public $showBarcode = true;

    public function mount($useBarcode = true)
    {
        if ($useBarcode) {
            $this->useBarcode = intval(Company::first()->barcode);
        } else {
            $this->useBarcode = true;
            $this->showBarcode = false;
        }

        $this->getProducts();
    }

    public function render()
    {
        return view('livewire.admin.products.search');
    }

    public function getProducts()
    {
        $this->productsArray = Product::with(['taxRates' => function ($query) {
            $query->select(['tax_rates.id', 'tax_rates.has_percentage', 'product_tax_rate.value', 'tax_rates.rate', 'tax_rates.tribute_id'])
                ->with('tribute:id,name');
        }])
            ->select(['products.id', 'products.barcode', 'products.reference', 'products.name', 'products.stock', 'products.quantity', 'products.units', 'products.price', 'products.has_inventory', 'products.has_presentations'])
            ->where('products.status', '0')
            ->orderBy('products.top', 'ASC')
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'barcode' => $item->barcode,
                    'reference' => $item->reference,
                    'name' => $item->name,
                    'stock' => $item->stockUnitsLabel,
                    'price' => $item->price,
                    'tax_rates' => $item->taxRates->map(function ($taxRate) {
                        return $taxRate->only('id', 'has_percentage', 'value', 'rate', 'tribute_name');
                    }),
                ];
            })
            ->toArray();

        $this->presentations = Presentation::select('id', 'name', 'quantity', 'price', 'product_id')->where('status', '0')->get()->toArray();
    }
}

<?php

namespace App\Http\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceProduct;
use WireUi\Traits\Actions;
use App\Exceptions\CustomException;

class AddProduct extends Component
{


    public $openModal = false;
    public $serviceId;

    public $search = '';
    public $product;
    public $products = [];
    public $product_id;
    public $quantity = 1;
    public $discountPercent = 0;
    public $unit_price = 0;
    public $total = 0;


    protected $listeners = ['openAddProduct' => 'openModalForService'];

    // Descuentos disponibles en porcentaje
    public $discountOptions = [
        0 => 0,
        5 => 5,
        10 => 10,
        15 => 15,
        20 => 20,
        25 => 25,
        30 => 30,
        35 => 35,
        40 => 40,
        45 => 45,
        50 => 50,
        80 => 80,
        95 => 95,
    ];

    protected $messages = [
        'product_id.required' => 'Debe seleccionar un producto.',
        'product_id.exists'   => 'El producto seleccionado no es válido.',
        'quantity.required'   => 'Debe ingresar una cantidad.',
        'quantity.integer'    => 'La cantidad debe ser un número entero.',
        'quantity.min'        => 'La cantidad debe ser al menos 1.',
        'unit_price.required'        => 'De ingresar un valor.',
        'unit_price.min'        => 'De ingresar un valor.',
    ];

    protected function rules()
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'discountPercent'   => 'required|numeric|min:0|max:100',
            'unit_price' => 'required|numeric|min:1',
        ];
    }

    public function updatedProductId()
    {
        // Cuando el usuario selecciona un producto, precargar el precio
        $product = Product::find($this->product_id);
        if ($product) {
            $this->unit_price = $product->price;
        }
    }

    public function openModalForService($serviceId)
    {
        $this->serviceId = $serviceId;
        $this->resetForm();
        $this->openModal = true;
    }

    public function updatedSearch()
    {
        // Buscar por código exacto o nombre parcial
        $this->products = Product::query()
            ->where('status', '0')
            ->where('terminal_id', auth()->user()->terminals->first()->id)
            ->where(function ($query) {
                $query->where('barcode', $this->search)
                    ->orWhere('name', 'like', "%{$this->search}%");
            })
            ->limit(10)
            ->get()
            ->toArray();

        // Si coincide código exacto → seleccionar automáticamente
        $found = Product::where('barcode', $this->search)->first();
        if ($found) {
            $this->selectProduct($found->id);
        }
    }

    public function selectProduct($productId)
    {
        $this->product = Product::find($productId);

        if ($this->product) {
            $this->product_id = $this->product->id;
            $this->unit_price = $this->product->price;
            $this->calculateTotal();
            $this->products = [];
            $this->search = $this->product->name;
        }
    }

    public function updatedQuantity()
    {
        $this->calculateTotal();
    }

    public function updatedDiscountPercent()
    {
        $this->calculateTotal();
    }

    private function calculateTotal()
    {
        $subtotal = (int) ($this->quantity ?? 0) * (float) $this->unit_price;
        $discountValue = ($subtotal * $this->discountPercent) / 100;
        $this->total = $subtotal - $discountValue;
    }

    public function save()
    {
        $this->validate();

        if (!$this->product) {
            return $this->emit('error', "Debe seleccionar un producto válido.");
        }

        if (!$this->product->has_inventory && $this->product->stock < $this->quantity) {
            return $this->emit('error', "No se pudo completar la compra. Solo quedan {$this->product->stock} unidades de {$this->product->name}.");
        }

        // Lógica de guardado
        ServiceProduct::create([
            'service_id'  => $this->serviceId,
            'product_id'  => $this->product->id,
            'quantity'    => $this->quantity,
            'unit_price'  => $this->unit_price,
            'discount'    => ($this->quantity * $this->unit_price) * ($this->discountPercent / 100),
            'total'       => $this->total,
        ]);

        if (!$this->product->has_inventory) {
            Product::where('id', $this->product->id)->decrement('stock', $this->quantity);
        }


        $this->emit('success', 'El producto fue agregado correctamente al servicio.');
        $this->emitTo('admin.services.edit', 'refreshProductDetails');

        $this->openModal = false;
    }

    public function resetForm()
    {
        $this->reset(['search', 'product', 'products', 'quantity', 'discountPercent', 'unit_price', 'total']);
        $this->quantity = 1;
        $this->discountPercent = 0;
        $this->total = 0;
    }

    public function render()
    {
        return view('livewire.admin.services.add-product');
    }
}

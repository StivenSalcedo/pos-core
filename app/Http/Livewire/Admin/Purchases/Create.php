<?php

namespace App\Http\Livewire\Admin\Purchases;

use App\Http\Controllers\Log;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Purchase;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component {

    protected $listeners=['setProvider', 'setProduct'];

    public $products, $provider, $keySelected, $update=false;

    public $product=[
        'product_id' => '',
        'bacode' => '',
        'reference' => '',
        'name' => '',
        'amount' => '',
        'stock' => '',
        'quantity' => '',
        'stock_units' => '',
        'units' => '',
        'new_units' => '',
        'cost' => '',
        'cost_unit' => '',
        'price' => '',
        'total' => '',
        'has_presentations' => '',
    ];

    protected $rules = [
        'product.amount' => 'required|integer|min:1|max:99999999',
        'product.new_units' => 'required|integer|min:0|max:99999999',
        'product.cost' => 'required|integer|min:0|max:99999999',
        'product.cost_unit' => 'required|integer|min:0|max:99999999',
        'product.price' => 'required|integer|min:1|max:99999999',
    ];

    public function mount(){
        $this->provider = new Provider();
        $this->products = collect();
    }

    public function render() {
        return view('livewire.admin.purchases.create')->layoutData(['title' => 'Agregar compra']);
    }

    public function getNewStockProperty(){

        if (!$this->product['product_id']) return 0;

        if (!intval($this->product['has_presentations']) && is_numeric($this->product['amount']) && is_numeric($this->product['new_units'])) {

            $stock = $this->product['stock'] + $this->product['amount'];
            $units = ( $stock * $this->product['quantity'] ) + $this->product['units'] + $this->product['new_units'];
            $stock = (int) ($units / $this->product['quantity']);

            return $stock . ' - ' .  $units - ($stock * $this->product['quantity']);

        }else if(is_numeric($this->product['amount'])){

            return $this->product['stock'] + $this->product['amount'];

        }

        return $this->product['stock_units'];

    }

    public function getTotalProperty(){
        if (!intval($this->product['has_presentations'])) {
            if (is_numeric($this->product['amount']) && is_numeric($this->product['new_units']) && is_numeric($this->product['cost']) && is_numeric($this->product['cost_unit'])) {
                $total = $this->product['amount'] * $this->product['cost'];
                $total = $total + ($this->product['new_units'] * $this->product['cost_unit']);
                return $total;
            }
        }else{
            if (is_numeric($this->product['amount']) && is_numeric($this->product['cost'])) {
                 return $this->product['amount'] * $this->product['cost'];
            }
        }
    }

    public function setProduct(Product $product){
        $this->product['product_id'] = $product->id;
        $this->product['barcode'] = $product->barcode;
        $this->product['reference'] = $product->reference;
        $this->product['name'] = $product->name;
        $this->product['amount'] = 0;
        $this->product['stock'] = $product->stock;
        $this->product['quantity'] = $product->quantity;
        $this->product['stock_units'] = $product->stockUnitsLabel;
        $this->product['units'] = $product->stockUnits;
        $this->product['new_units'] = 0;
        $this->product['cost'] = $product->cost;
        $this->product['cost_unit'] = !intval($product['has_presentations']) ? (int) ($product->cost / $product->quantity) : 0;
        $this->product['price'] = $product->price;
        $this->product['has_presentations'] = (int) $product->has_presentations;
    }

    public function setProvider(Provider $provider){
        $this->provider = $provider;
    }

    public function addProduct(){

        if ($this->product['cost'] > $this->product['price']) {
            return $this->addError('product.cost', 'El costo no debe ser mayor a precio de venta');
        }

        $this->validate();

        $collect = $this->getDataForm('product');
        $this->products = $this->products->concat($collect);

        $this->resetForm();
    }

    public function updateProduct(){

        if ($this->product['cost'] > $this->product['price']) {
            return $this->addError('product.cost', 'El costo no debe ser mayor al precio de venta');
        }

        $this->validate();
        $collect = $this->getDataForm($this->keySelected);
        $this->products = $this->products->replace($collect);

        $this->resetForm();
    }

    public function edit($key){
        $this->keySelected = $key;
        $this->update = true;
        $this->product = $this->products->get($key);
    }

    public function getDataForm($key) {
        $this->product['total'] = $this->total;
        return collect([$key => $this->product]);
    }

    public function cancel(){
        $this->resetForm();
    }

    public function delete($key){
        $this->products->forget($key);
    }

    private function resetForm(){
        $this->update = false;
        $this->reset('product');
        $this->forgetComputed('total');
    }

    public function store(){

        if(!$this->provider->id) return $this->emit('alert', 'Selecciona el proveedor');

        if(!count($this->products)) return $this->emit('alert', 'Agrega uno o más productos');

        try {

            DB::beginTransaction();

                $purchase = Purchase::create([
                    'total' => $this->products->sum('total'),
                    'provider_id' => $this->provider->id,
                ]);

                foreach ($this->products as $item) {

                    //se filtra solo los atributos que se van a registrar en la tabla purchase_details
                    $arrItem = Arr::only($item, ['new_units', 'cost_unit', 'amount', 'cost', 'total', 'product_id']);
                    $arrItem['units'] = $item['new_units'];

                    $purchase->details()->create($arrItem);

                    $product = Product::findOrFail($item['product_id']);

                    if(intval($product->has_inventory)) throw new Exception("El producto que intento agregar a la compra no lleva inventario");

                    if (!intval($product->has_presentations)) {
                        $this->updateStockAndUnits($item, $product);
                    }else{
                        $this->updateOnlyStock($item, $product);
                    }

                }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage(), $this->products->toArray());
            return $this->emit('error', 'Ha sucedido un error inesperado. Vuelve a intentarlo');
        }

        $this->reset();
        $this->provider = new Provider();
        $this->product = new Product();
        $this->products = collect([]);

        return redirect()->route('admin.purchases.index');

    }

     /**
     * Actualiza solo el campo stock
     * @param array  $item
     * @param Product $product
     */
    private function updateOnlyStock($item, Product $product){

        Product::where('id', $item['product_id'])->update([
            'cost' => $item['cost'],
            'price' => $item['price'],
            'stock' => $product->stock + $item['amount'],
        ]);

    }

    /**
     * Actualiza el campo stock y unidades
     * @param array  $item
     * @param Product $product
     */
    private function updateStockAndUnits($item, Product $product){

        $stock = $product->stock + $item['amount'];
        $units = ( $stock * $item['quantity'] ) + $item['units'] + $item['new_units'];
        $stock = (int) ($units / $item['quantity']);

        Product::where('id', $item['product_id'])->update([
            'cost' => $item['cost'],
            'price' => $item['price'],
            'units' => $units,
            'stock' => $stock,
        ]);

    }

      /**
     * Devuelve una coleccion con los productos de la base de datos sin repetirse y solo los que cuentan con presentaciones
     * @param \Illuminate\Support\Collection<TKey, TValue>
     * @return \Illuminate\Support\Collection<TKey, TValue>
     */
    private function getUniqueProductsDB(Collection $collection) : Collection{

        $collection->transform(fn($item) => $item['product_id']);
        $ids = $collection->unique();
        return Product::whereIn('id', $ids)->where('has_presentations', '0')->get();
    }


}

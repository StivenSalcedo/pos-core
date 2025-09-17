<?php

namespace App\Http\Livewire\Admin\Products;

use App\Http\Controllers\Log;
use App\Models\Category;
use App\Models\Presentation;
use App\Models\Product;
use App\Services\ModuleService;
use App\Traits\LivewireTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Edit extends Component
{
    use LivewireTrait;

    protected $listeners = ['openEdit', 'setPresentation', 'refreshCategories', 'setTaxRates'];

    public $product, $openEdit = false;

    public $category_id = '';

    public $presentations, $units, $categories;

    public Collection $tax_rates;

    public $is_inventory_enabled = false;

    public function mount()
    {
        $this->refreshCategories();
        $this->product = new Product();
        $this->presentations = collect();
        $this->tax_rates = collect();
    }

    public function render()
    {
        return view('livewire.admin.products.edit');
    }

    protected function rules()
    {
        return [
            'product.barcode' => 'required',
            'product.reference' => 'required',
            'product.category_id' => 'nullable',
            'product.name' => 'required',
            'product.cost' => 'required',
            'product.price' => 'required',
            'product.has_inventory' => 'required',
            'product.stock' => 'required',
            'units' => 'required',
            'product.top' => 'required',
            'product.status' => 'required',
            'product.quantity' => 'nullable',
            'product.has_presentations' => 'required',
            'presentations' => 'nullable',
        ];
    }

    public function updatedProductHasInventory($value)
    {
        if ($value) {
            $this->product->has_presentations = '1';
        }
    }

    public function updatedProductHasPresentations($value)
    {
        if ($value) {
            $this->product->quantity = '';
            $this->presentations = collect();
        }
    }

    public function refreshCategories()
    {
        $this->categories = Category::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    }

    public function setTaxRates($taxRates)
    {
        $this->tax_rates = collect($taxRates);
    }

    public function openEdit(Product $product)
    {
        $this->resetValidation();
        $this->presentations = collect();
        $this->product = $product;
        $this->category_id = $product->category_id == null ? '' : $product->category_id;

        if (!intval($product->has_presentations)) {

            $this->units = $product->stockUnits;

            $this->presentations = $product->presentations->map(function ($item, $key) {
                return [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            });
        } else {
            $this->units = 0;
            $this->product->quantity = '';
        }

        $this->getTaxRates();
        $this->is_inventory_enabled = ModuleService::isEnabled('inventario');
        $this->openEdit = true;
    }

    public function openTaxRates()
    {
        $data = [
            'nameComponent' => $this->getName(),
            'taxRates' => $this->tax_rates
        ];

        $this->emitTo('admin.products.tax-rates', 'openModal', $data);
    }

    protected function getTaxRates()
    {
        $this->tax_rates = $this->product->taxRates()->get(['tax_rates.id', 'name', 'value', 'rate', 'has_percentage', 'tribute_id'])
            ->append(['format_rate', 'format_name', 'format_name2'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'value' => $item->value,
                    'rate' => $item->rate,
                    'has_percentage' => $item->has_percentage,
                    'format_rate' => $item->format_rate,
                    'format_name' => $item->format_name,
                    'format_name2' => $item->format_name2,
                ];
            });
    }

    public function setPresentation($array, $key)
    {
        if ($key !== null) {
            $this->presentations->put($key, $array);
        } else {
            $this->presentations->push($array);
        }
    }

    public function editPresentation($index)
    {
        $this->emitTo('admin.products.presentations', 'openPresentations', $this->getName(), $this->presentations->get($index), $index);
    }

    public function removePresentation($index)
    {
        $this->presentations->forget($index);
    }

    protected function formatData(): array
    {
        $arrayProperties = ['product.barcode', 'product.reference', 'product.category_id', 'product.name', 'tax_rates', 'product.cost', 'product.price', 'product.has_inventory', 'product.stock', 'units', 'product.quantity', 'product.has_presentations', 'presentations', 'product.top', 'product.status'];

        $this->applyTrim($arrayProperties);

        $dataArray = $this->only($arrayProperties);

        foreach ($dataArray as $key => $value) {
            $data[str_replace('product.', '', $key)] = $value;
        }

        if (!ModuleService::isEnabled('inventario') || intval($this->product->has_inventory)) {
            $data['stock'] = 0;
            $data['has_inventory'] = '1';
            $data['has_presentations'] = '1';
            $data['quantity'] = 0;
            $data['units'] = 0;
            $data['presentations'] = collect();
        }

        if (ModuleService::isEnabled('inventario') && !intval($this->product->has_inventory) && intval($this->product->has_presentations)) {
            $data['quantity'] = 0;
            $data['units'] = 0;
            $data['presentations'] = collect();
        }

        $data['category_id'] = $data['category_id'] === '' ? null : $data['category_id'];
        $data['presentations'] = $data['presentations']->toArray();

        $data['tax_rates'] = $this->tax_rates->map(fn ($item) => collect($item)->only('id', 'value'))->toArray();

        return $data;
    }

    public function update()
    {
        $data = $this->formatData();

        $rules = [
            'barcode' => 'required|string|unique:products,barcode,' . $this->product->id,
            'reference' => 'required|string|unique:products,reference,' . $this->product->id,
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|min:3|max:250',
            'cost' => 'required|integer|max:99999999',
            'price' => 'required|integer|max:99999999',
            'has_inventory' => 'required|min:0|max:1',
            'stock' => 'required|integer|min:0|max:9999999',
            'units' => 'required|integer|min:0|max:9999999',
            'top' => 'required|integer|min:0|max:1',
            'status' => 'required|integer|min:0|max:1',
            'has_presentations' => 'required|integer|min:0|max:1',
            'quantity' => 'exclude_if:has_presentations,1|required|integer|min:1|max:99999999',
            'presentations' => 'nullable|exclude_if:has_presentations,1|array|min:1',
            'tax_rates' => 'array|min:1',
            'tax_rates.*.id' => 'required|integer|exists:tax_rates,id',
            'tax_rates.*.value' => 'required|integer|min:0|max:999999999',
        ];

        $attributes = [
            'name' => 'nombre',
            'units' => 'unidades',
            'quantity' => 'unidades x producto',
            'presentations' => 'presentaciones',
            'tax_rates' => 'impuestos',
        ];

        $messages = [
            'presentations.min' => 'Agrega una o más presentaciones',
        ];

        $data = Validator::make($data, $rules, $messages, $attributes)->validate();

        if ($data['cost'] && $data['price']) {
            if ($data['cost'] >= $data['price']) {
                return $this->addError('cost', 'El costo no debe ser mayor o igual al precio');
            }
        }

        if (!intval($data['has_presentations'])) {
            $data['units'] = ($data['stock'] * $data['quantity']) + $data['units'];
        }

        try {

            DB::beginTransaction();

            $this->product->fill(Arr::except($data, ['presentations']));

            $this->product->save();

            $this->product->taxRates()->sync($this->tax_rates->mapWithKeys(fn ($item) => [$item['id'] => ['value' => $item['value']]]));

            $this->product->presentations()->delete();

            if (!intval($data['has_presentations'])) {

                foreach ($this->presentations as  $presentation) {
                    if ($presentation['quantity'] > $data['quantity']) return $this->addError('presentation', "La cantidad supera las unidades por producto de la presentación {$presentation['name']}");
                    $presentation['product_id'] = $this->product->id;
                    Presentation::create($presentation);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage(), ['product' => $this->product->toArray(), 'presentation' => $this->presentations]);
            return $this->emit('error', 'Ha ocurrido un error inesperado al actualizar el producto. Vuelve a intentarlo');
        }

        $this->resetExcept('tax_rates', 'categories');
        $this->tax_rates = collect();
        $this->resetValidation();
        $this->presentations = collect();
        $this->product = new Product();

        $this->emit('success', 'Producto actualizado con éxito');
        $this->emitTo('admin.products.index', 'render');
    }
}

<?php

namespace App\Http\Livewire\Admin\Products;

use App\Http\Controllers\Log;
use App\Models\Category;
use App\Models\Presentation;
use App\Models\Product;
use App\Services\ModuleService;
use App\Traits\LivewireTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use App\Models\Terminal;
use App\Models\Provider;
use App\Models\Brand;

class Create extends Component
{

    use LivewireTrait;

    protected $listeners = [
        'openCreate',
        'setPresentation',
        'refreshCategories',
        'setTaxRates',
        'set-provider' => 'setProviderFromModal',
        'set-brand' => 'setBrandFromModal'
    ];

    public $openCreate = false;

    public $barcode, $reference, $category_id = "", $name, $cost, $price, $has_inventory = '1', $stock, $units = 0, $quantity, $terminal_id = "";

    public  $entrepreneur_price = 0, $wholesale_price = 0, $provider_id = '', $brand_id = '';

    public $has_presentations = '1';

    public Collection $tax_rates;

    public $categories, $presentations, $terminals;

    public $is_inventory_enabled = false;

    public $selectedProvider = [];

    public $providers = [];

    public $searchProvider = '';

    public $selectedBrand = [];

    public $brands = [];

    public $searchBrand = '';

    public function mount()
    {
        $this->refreshCategories();
        $this->presentations = collect();
        $this->tax_rates = collect();
        $this->is_inventory_enabled = ModuleService::isEnabled('inventario');
    }

    public function render()
    {
        return view('livewire.admin.products.create');
    }

    public function updatedHasInventory($value)
    {
        if ($value) {
            $this->has_presentations = '1';
        }
    }

    public function updatedHasPresentations($value)
    {
        if ($value) {
            $this->quantity = '';
            $this->presentations = collect();
        }
    }

    public function setPresentation($array, $key)
    {
        if ($key !== null) {
            $this->presentations->put($key, $array);
        } else {
            $this->presentations->push($array);
        }
    }

    public function setTaxRates($taxRates)
    {
        $this->tax_rates = collect($taxRates);
    }

    public function refreshCategories()
    {
        $this->categories = Category::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $this->terminals = Terminal::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    }

    public function editPresentation($index)
    {
        $this->emitTo('admin.products.presentations', 'openPresentations', $this->getName(), $this->presentations->get($index), $index);
    }

    public function openTaxRates()
    {
        $data = [
            'nameComponent' => $this->getName(),
            'taxRates' => $this->tax_rates
        ];

        $this->emitTo('admin.products.tax-rates', 'openModal', $data);
    }

    public function removePresentation($index)
    {
        $this->presentations->forget($index);
    }

    public function openCreate()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->setDefaults();
       
        $this->openCreate = true;
    }

    protected function formatData()
    {
        $arrayProperties = ['barcode', 'reference', 'category_id', 'name', 'cost', 'wholesale_price', 'entrepreneur_price', 'price', 'has_inventory', 'stock', 'units', 'quantity', 'has_presentations', 'presentations', 'terminal_id', 'brand_id', 'provider_id'];

        $this->applyTrim($arrayProperties);

        $data = $this->only($arrayProperties);

        if (!ModuleService::isEnabled('inventario') || intval($this->has_inventory)) {
            $data['stock'] = 0;
            $data['has_presentations'] = '1';
            $data['quantity'] = 0;
            $data['units'] = 0;
            $data['presentations'] = collect();
        }

        if (ModuleService::isEnabled('inventario') && !intval($this->has_inventory) && intval($this->has_presentations)) {
            $data['quantity'] = 0;
            $data['units'] = 0;
            $data['presentations'] = collect();
        }

        $data['category_id'] = $data['category_id'] === '' ? null : $data['category_id'];
        $data['presentations'] = $data['presentations']->toArray();

        $data['tax_rates'] = $this->tax_rates->map(fn($item) => collect($item)->only('id', 'value'))->toArray();

        return $data;
    }

    public function store()
    {
        $data = $this->formatData();

        $rules = [
            'barcode' => 'required|string|unique:products',
            'reference' => 'required|string|unique:products',
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|min:3|max:250',
            'cost' => 'required|integer|min:0|max:99999999',
            'entrepreneur_price' => 'required|integer|min:0|max:99999999',
            'wholesale_price' => 'required|integer|min:0|max:99999999',
            'price' => 'required|integer|min:0|max:99999999',
            'has_inventory' => 'required|min:0|max:1',
            'stock' => 'required|integer|min:0|max:99999999',
            'units' => 'required|integer|min:0|max:99999999',
            'has_presentations' => 'required|integer|min:0|max:1',
            'quantity' => 'nullable|exclude_if:has_presentations,1|integer|min:1|max:99999999',
            'presentations' => 'nullable|exclude_if:has_presentations,1|array|min:1',
            'tax_rates' => 'array|min:1',
            'tax_rates.*.id' => 'required|integer|exists:tax_rates,id',
            'tax_rates.*.value' => 'required|integer|min:0|max:999999999',
            'terminal_id' => 'required|exists:terminals,id',
            'provider_id' => 'nullable|exists:providers,id',
            'brand_id' => 'nullable|exists:brands,id',
        ];

        $attributes = [
            'name' => 'nombre',
            'quantity' => 'unidades x producto',
            'presentations' => 'presentaciones',
            'tax_rates' => 'impuestos',
            'terminal_id' => 'sede',
            'provider_id' => 'proveedor',
            'brand_id' => 'marca',
            'category_id' => 'categoria',
        ];

        $messages = [
            'presentations.min' => 'Debes agregar una o mas presentaciones',
        ];

        if ($this->cost && $this->price) {
            if ($this->cost >= $this->price) {
                return $this->addError('cost', 'El costo no debe ser mayor o igual al precio');
            }
        }

        $data = Validator::make($data, $rules, $messages, $attributes)->validate();

        try {

            DB::beginTransaction();

            if (!intval($this->has_presentations)) {

                if ($this->units >= $this->quantity) return $this->addError('units', 'Las unidades no pueden ser iguales o superiores las unidades por producto');

                $data['units'] = $this->stock * $this->quantity + $this->units;
            }

            $data['tax_rate_id'] = 5;

            $product = Product::create($data);

            $product->taxRates()->attach($this->tax_rates->mapWithKeys(fn($item) => [$item['id'] => ['value' => $item['value']]]));

            if (!intval($this->has_presentations)) {
                foreach ($this->presentations as  $item) {
                    if ($item['quantity'] > $this->quantity) return $this->addError('presentation', "La cantidad supera las unidades por producto de la presentación {$item['name']}");
                    $item['product_id'] = $product->id;
                    Presentation::create($item);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage(), ['product' => $data, 'presentation' => $this->presentations]);
        }

        $this->resetExcept('tax_rates', 'categories', 'terminals');
        $this->tax_rates = collect();
        $this->resetValidation();
        $this->presentations = collect();

        $this->emitTo('admin.products.index', 'render');
        $this->emit('success', 'Producto creado con éxito');
    }

    public function clearProvider()
    {
        $this->selectedProvider = null;
        $this->provider_id = null;
        $this->searchProvider = '';
    }

    public function setProviderFromModal($provider)
    {
        $this->selectProvider($provider['id']);
    }

    public function updatedSearchProvider()
    {
        if (strlen($this->searchProvider) > 1) {
            $this->providers = Provider::where('name', 'like', "%{$this->searchProvider}%")
                ->orWhere('no_identification', 'like', "%{$this->searchProvider}%")
                ->limit(10)->get();
        } else {
            $this->providers = [];
        }
    }

    public function selectProvider($id)
    {
        $provider = Provider::find($id);
        if ($provider) {
            $this->selectedProvider = $provider->only(['id', 'name', 'no_identification']);
            $this->provider_id = $provider->id;
            $this->providers = [];
            $this->searchProvider = $provider->name;
        }
    }


    public function clearBrand()
    {
        $this->selectedBrand = null;
        $this->brand_id = null;
        $this->searchBrand = '';
    }

    public function setBrandFromModal($provider)
    {
        $this->selectBrand($provider['id']);
    }

    public function updatedSearchBrand()
    {
        if (strlen($this->searchBrand) > 1) {
            $this->brands = Brand::where('name', 'like', "%{$this->searchBrand}%")
                ->limit(10)->get();
        } else {
            $this->brands = [];
        }
    }

    public function selectBrand($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            $this->selectedBrand = $brand->only(['id', 'name']);
            $this->brand_id = $brand->id;
            $this->brands = [];
            $this->searchBrand = $brand->name;
        }
    }

    protected function setDefaults()
    {
        $this->barcode = null;
        $this->reference = null;
        $this->category_id = '';
        $this->name = null;
        $this->cost = null;
        $this->price = null;
        $this->entrepreneur_price = 0;
        $this->wholesale_price = 0;

        $this->has_inventory = '1';
        $this->has_presentations = '1';

        $this->stock = 0;
        $this->units = 0;
        $this->quantity = null;

        $this->terminal_id = '';
        $this->provider_id = null;
        $this->brand_id = null;

        $this->presentations = collect();
        $this->tax_rates = collect();

        $this->selectedProvider = [];
        $this->selectedBrand = [];
        $this->searchProvider = '';
        $this->searchBrand = '';

        $this->is_inventory_enabled = ModuleService::isEnabled('inventario');
    }
}

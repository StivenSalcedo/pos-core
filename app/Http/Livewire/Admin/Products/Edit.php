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
use App\Models\Terminal;
use App\Models\Provider;
use App\Models\Brand;
use Livewire\WithFileUploads;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class Edit extends Component
{
    use LivewireTrait;
    use WithFileUploads;

    protected $listeners = [
        'openEdit',
        'setPresentation',
        'refreshCategories',
        'setTaxRates',
        'set-provider' => 'setProviderFromModal',
        'set-brand' => 'setBrandFromModal',
        'refreshImages' => '$refresh',
    ];

    public $product, $openEdit = false;

    public $category_id = '', $terminal_id = '';

    public $presentations, $units, $categories, $terminals;

    public Collection $tax_rates;

    public $is_inventory_enabled = false;

    public $selectedProvider = [];

    public $providers = [];

    public $searchProvider = '';

    public $selectedBrand = [];

    public $brands = [];

    public $searchBrand = '';

    public $clone = false;
    public $photo;
    public $openUploadModal = false;
    public $cameraPhoto;

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
            'product.wholesale_price' => 'required',
            'product.entrepreneur_price' => 'required',
            'product.price' => 'required',
            'product.has_inventory' => 'required',
            'product.stock' => 'required',
            'units' => 'required',
            'product.top' => 'required',
            'product.status' => 'required',
            'product.quantity' => 'nullable',
            'product.has_presentations' => 'required',
            'presentations' => 'nullable',
            'product.terminal_id' => 'required',
            'product.provider_id' => 'nullable|exists:providers,id',
            'product.brand_id' => 'nullable|exists:brands,id',
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
        $this->terminals = Terminal::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    }

    public function setTaxRates($taxRates)
    {
        $this->tax_rates = collect($taxRates);
    }

    public function openEdit(Product $product, bool $clone = false)
    {
        $this->resetValidation();
        $this->presentations = collect();
        $this->product = $product;
        

        $this->category_id = $product->category_id == null ? '' : $product->category_id;
        $this->terminal_id = $product->terminal_id == null ? '' : $product->terminal_id;

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

        if ($product->provider) {
            $this->selectedProvider = [
                'id' => $product->provider->id,
                'name' => $product->provider->name,
                'no_identification' => $product->provider->no_identification,
            ];
        } else {
            $this->selectedProvider = [];
        }


        if ($product->brand) {
            $this->selectedBrand = [
                'id' => $product->brand->id,
                'name' => $product->brand->name,
            ];
        } else {
            $this->selectedBrand = [];
        }

        $this->openEdit = true;
        if ($clone) {
            $this->clone = $clone;
            $this->product->id = null;
        }
        else
        {
             $this->clone = false;
        }
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
        $arrayProperties = ['product.barcode', 'product.reference', 'product.category_id', 'product.name', 'tax_rates', 'product.cost', 'product.price', 'product.has_inventory', 'product.stock', 'units', 'product.quantity', 'product.has_presentations', 'presentations', 'product.top', 'product.status', 'product.terminal_id'];

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

        $data['tax_rates'] = $this->tax_rates->map(fn($item) => collect($item)->only('id', 'value'))->toArray();

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
            'entrepreneur_price' => 'nullable|integer|max:99999999',
            'wholesale_price' => 'nullable|integer|max:99999999',
            'has_inventory' => 'required|min:0|max:1',
            'stock' => 'required|integer|min:0|max:9999999',
            'units' => 'required|integer|min:0|max:9999999',
            'top' => 'required|integer|min:0|max:1',
            'status' => 'required|integer|min:0|max:1',
            'has_presentations' => 'required|integer|min:0|max:1',
            'quantity' => 'exclude_if:has_presentations,1|required|integer|min:1|max:99999999',
            'presentations' => 'nullable|exclude_if:has_presentations,1|array|min:1',
            'tax_rates' => 'array|min:0',
            'tax_rates.*.id' => 'required|integer|exists:tax_rates,id',
            'tax_rates.*.value' => 'required|integer|min:0|max:999999999',
            'terminal_id' => 'required|exists:terminals,id',
            'provider_id' => 'nullable|exists:providers,id',
            'brand_id' => 'nullable|exists:brands,id',
        ];

        $attributes = [
            'name' => 'nombre',
            'units' => 'unidades',
            'quantity' => 'unidades x producto',
            'presentations' => 'presentaciones',
            'tax_rates' => 'impuestos',
            'terminal_id' => 'sede',
            'provider_id' => 'proveedor',
            'brand_id' => 'marca',
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

            $this->product->taxRates()->sync($this->tax_rates->mapWithKeys(fn($item) => [$item['id'] => ['value' => $item['value']]]));

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

        $this->resetExcept('tax_rates', 'categories', 'terminals');
        $this->tax_rates = collect();
        $this->resetValidation();
        $this->presentations = collect();
        $this->product = new Product();

        $this->emit('success', 'Producto actualizado con éxito');
        $this->emitTo('admin.products.index', 'render');
    }

    public function clearProvider()
    {
        $this->selectedProvider = null;
        $this->product->provider_id = null;
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
            $this->product->provider_id = $provider->id;
            $this->providers = [];
            $this->searchProvider = $provider->name;
        }
    }


    public function clearBrand()
    {
        $this->selectedBrand = null;
        $this->product->brand_id = null;
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
            $this->product->brand_id = $brand->id;
            $this->brands = [];
            $this->searchBrand = $brand->name;
        }
    }

     public function openPhotoUpload()
    {
        $this->reset('photo');
        $this->resetValidation();
        $this->openUploadModal = true;
    }

    public function saveCameraPhoto($base64Image)
    {
        try {
            if (!$base64Image) {
                $this->emit('error', 'No se pudo obtener la imagen de la cámara.');

                return;
            }

            // Quitar encabezado "data:image/png;base64,..."
            $imageData = explode(',', $base64Image)[1] ?? null;
            if (!$imageData) throw new \Exception("Formato inválido de imagen");

            $binary = base64_decode($imageData);

            // Crear nombre y ruta
            $filename = 'camera_' . Str::random(8) . '.jpg';
            $path = 'services/' . $this->service->id . '/' . $filename;

            Storage::disk('public')->put($path, $binary);

            // Guardar registro
            ProductImage::create([
                'product_id' => $this->service->id,
                'path'       => $path,
                'filename'   => $filename,
            ]);


            $this->emit('success', 'La imagen capturada se subió correctamente.');


            $this->emitSelf('refreshImages');
        } catch (\Exception $e) {
            $this->emit('error', 'Error al guardar:' + $e->getMessage());
        }
    }

    public function savePhoto()
    {
        $this->validatePhoto();
        $path = $this->photo->storeAs('images/products', $this->product->id . '_' . $this->photo->getClientOriginalName(), 'public');
        //$path = $this->photo->store('services/' . $this->service->id, 'public');

        ProductImage::create([
            'product_id' => $this->product->id,
            'path' => $path,
            'filename' => $this->photo->getClientOriginalName(),
        ]);

        $this->emit('success', 'La imagen se guardó correctamente.');


        $this->reset('photo');
        $this->openUploadModal = false;
        $this->emitSelf('refreshImages');
    }

    public function removePhoto($id)
    {
        $attachment = ProductImage::findOrFail($id);

        // Eliminar archivo del disco
        if (Storage::disk('public')->exists($attachment->path)) {
            Storage::disk('public')->delete($attachment->path);
        }

        $attachment->delete();

        $this->emit('success', 'La imagen fue eliminada correctamente.');


        $this->emitSelf('refreshImages');
    }
     public function validatePhoto()
    {
        $this->validate([
            'photo' => 'required|image|max:4096',
        ]);
    }
}

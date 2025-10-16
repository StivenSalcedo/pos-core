<?php

namespace App\Http\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Service;
use App\Models\Customer;
use App\Models\User;
use App\Models\ServiceState;
use App\Models\EquipmentType;
use App\Models\Brand;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Edit extends Component
{
    public Service $service;
    public  $responsibles, $technicians, $states, $equipmentTypes, $brands;
    public $tab = 'main'; // tab activa
    public $selectedCustomer = [];
    public $customers = [];
    public $details = [];
    public $products=[];
    public $searchCustomer = '';
    protected $listeners = [
        'openEdit',
        'refreshdata',
        'refreshBrands',
        'set-customer' => 'setCustomerFromModal',
        'refreshServiceDetails',
        'refreshProductDetails'
    ];
    protected $rules = [
        'service.date_entry' => 'required|date',
        'service.date_due' => 'required|date|after_or_equal:service.date_entry',
        'service.customer_id' => 'required|exists:customers,id',
        'service.responsible_id' => 'required|exists:users,id',
        'service.tech_assigned_id' => 'nullable|exists:users,id',
        'service.state_id' => 'required|exists:service_states,id',
        'service.model' => 'required|string|max:150',
        'service.problem_description' => 'nullable|string|max:500',
        'service.diagnosis' => 'nullable|string|max:500',
        'service.equipment_type_id' => 'required|exists:equipment_types,id',
        'service.document_number' => 'nullable|string|max:255',
        'service.password' => 'nullable|string|max:255',
        'service.accessories' => 'nullable|string|max:255',
        'service.user' => 'nullable|string|max:255',
        'service.brand_id' => 'nullable',

    ];

    public function mount(Service $service)
    {
        $this->service = $service;
        //dd($this->service);
        Log::debug('Llegó a setCustomerFromModal', ['data' => $service]);
        $this->responsibles = User::pluck('name', 'id');
        $this->technicians = User::pluck('name', 'id');
        $this->states = ServiceState::pluck('name', 'id');
        $this->equipmentTypes = EquipmentType::pluck('name', 'id');

        $this->brands = Brand::pluck('name', 'id');
        $this->service->load(['details.component', 'details.brand']);

        if ($service->customer) {
            $this->selectedCustomer = [
                'id' => $service->customer->id,
                'names' => $service->customer->names,
                'no_identification' => $service->customer->no_identification,
            ];
        }

        if (is_null($this->service->state_id) && $this->states->isNotEmpty()) {
            $this->service->state_id = $this->states->keys()->first();
            $this->service->load(['details.component', 'details.brand']);
        }
        $this->refreshServiceDetails();
        $this->refreshProductDetails();
    }

    public function refreshdata($newEquipmentTypeId = null)
    {
        $this->equipmentTypes = EquipmentType::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        // Si se envió un ID válido, seleccionarlo automáticamente
        if ($newEquipmentTypeId && isset($this->equipmentTypes[$newEquipmentTypeId])) {
            $this->service->equipment_type_id = $newEquipmentTypeId;
        }
    }

    public function refreshBrands($newBrandId = null)
    {
        $this->brands = Brand::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        // Si se envió un ID válido, seleccionarlo automáticamente
        if ($newBrandId && isset($this->brands[$newBrandId])) {
            $this->service->brand_id = $newBrandId;
        }
    }

    public function refreshServiceDetails()
    {
        $this->details = $this->service->details()->with('component', 'brand')->get()->toArray();
    }

    public function refreshProductDetails()
    {
        $this->products = $this->service->products()->with('product')->get()->toArray();
    }

    public function update()
    {
        $this->validate();
        $this->service->save();
        $this->emit('success', 'Servicio actualizado correctamente');
    }

    public function clearCustomer()
    {
        $this->selectedCustomer = null;
        $this->service->customer_id = null;
        $this->searchCustomer = '';
    }

    public function setCustomerFromModal($customer)
    {
        $this->selectCustomer($customer['id']);
    }

    public function updatedSearchCustomer()
    {
        if (strlen($this->searchCustomer) > 1) {
            $this->customers = Customer::where('names', 'like', "%{$this->searchCustomer}%")
                ->orWhere('no_identification', 'like', "%{$this->searchCustomer}%")
                ->limit(10)->get();
        } else {
            $this->customers = [];
        }
    }

    public function selectCustomer($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $this->selectedCustomer = $customer->only(['id', 'names', 'no_identification']);
            $this->service->customer_id = $customer->id;
            $this->customers = [];
            $this->searchCustomer = $customer->names;
        }
    }

    public function deleteDetail($id)
    {
        $detail = $this->service->details()->find($id);

        if ($detail) {
            $detail->delete();
            $this->refreshServiceDetails();
        }
    }

    public function deleteProduct($id)
    {
        $product = $this->service->products()->find($id);

        if ($product) {
            $product->delete();
            $this->refreshProductDetails();
             Product::where('id', $product->product_id)->increment('stock', $product->quantity);
        }
    }

    


    public function render()
    {
        return view('livewire.admin.services.edit');
    }
}

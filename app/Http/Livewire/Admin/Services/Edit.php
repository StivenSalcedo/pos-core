<?php

namespace App\Http\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Service;
use App\Models\Customer;
use App\Models\User;
use App\Models\ServiceState;
use App\Models\EquipmentType;
use App\Models\Brand;
use Carbon\Carbon;

class Edit extends Component
{
    public Service $service;
    public $customers, $responsibles, $technicians, $states, $equipmentTypes, $brands;
    public $tab = 'main'; // tab activa

    protected $rules = [
        'service.date_entry' => 'required|date',
        'service.date_due' => 'required|date|after_or_equal:service.date_entry',
        'service.customer_id' => 'required|exists:customers,id',
        'service.responsible_id' => 'required|exists:users,id',
        'service.tech_assigned_id' => 'nullable|exists:users,id',
        'service.state_id' => 'nullable|exists:service_states,id',
        'service.model' => 'required|string|max:150',
        'service.description' => 'nullable|string|max:500',
        'service.diagnosis' => 'nullable|string|max:500',
    ];

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->customers = Customer::pluck('names', 'id');
        $this->responsibles = User::pluck('name', 'id');
        $this->technicians = User::pluck('name', 'id');
        $this->states = ServiceState::pluck('name', 'id');
       
        $this->service->load(['details.component', 'details.brand']);
        $this->brands = Brand::pluck('name', 'id');
    }

    public function save()
    {
        $this->validate();
        $this->service->save();
        $this->emit('success', 'Servicio actualizado correctamente');
    }

    public function render()
    {
        return view('livewire.admin.services.edit');
    }
}

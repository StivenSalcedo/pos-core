<?php

namespace App\Http\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Service;
use App\Models\Component as ComponentModel;
use App\Models\Brand;
use App\Models\ServiceDetail;
use WireUi\Traits\Actions;

class AddComponent extends Component
{


    public $openModal = false;
    public $serviceId;
    public $components = [];
    public $brands = [];

    public $form = [
        'component_id' => null,
        'brand_id' => null,
        'reference' => 'SIN REFERENCIA',
        'capacity' => 'N/A',
        'quantity' => 1,
    ];

    protected $listeners = ['openAddComponent' => 'openModalForService'];

    public function mount()
    {
        $this->components = ComponentModel::orderBy('name')->pluck('name', 'id')->toArray();
        $this->brands = Brand::orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function openModalForService($serviceId)
    {
        $this->serviceId = $serviceId;
        $this->resetValidation();
        $this->reset('form');
        $this->form['reference'] = 'SIN REFERENCIA';
        $this->form['capacity'] = 'N/A';
        $this->form['quantity'] = 1;
        $this->openModal = true;
    }

    public function render()
    {
        return view('livewire.admin.services.add-component');
    }

    public function save()
    {
        $this->validate([
            'form.component_id' => 'required|exists:equipment_types,id',
            'form.quantity' => 'required|integer|min:1',
            'form.brand_id' => 'nullable|exists:brands,id',
        ]);

        $service = Service::findOrFail($this->serviceId);

        ServiceDetail::create([
            'service_id' => $service->id,
            'component_id' => $this->form['component_id'],
            'brand_id' => $this->form['brand_id'],
            'reference' => $this->form['reference'] ?: 'SIN REFERENCIA',
            'capacity' => $this->form['capacity'] ?: 'N/A',
            'quantity' => $this->form['quantity'] ?? 1,
        ]);
        $this->emit('success', 'El componente fue agregado correctamente al servicio.');

        $this->emitTo('admin.services.edit', 'refreshServiceDetails');
        $this->openModal = false;
    }
}

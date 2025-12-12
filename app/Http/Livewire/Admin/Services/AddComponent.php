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
    public $editId = 0;
    public $components = [];
    public $brands = [];

    public $form = [
        'component_id' => null,
        'brand_id' => null,
        'reference' => 'SIN REFERENCIA',
        'capacity' => 'N/A',
        'quantity' => 1,
    ];

    protected $listeners = [
        'openAddComponent' => 'openModalForService',
        'refreshComponents',
         'refreshBrands',
    ];

    public function mount()
    {
        $this->components = ComponentModel::orderBy('name')->pluck('name', 'id')->toArray();
        $this->brands = Brand::orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function openModalForService($serviceId, $editId = 0)
    {
        $this->serviceId = $serviceId;
        $this->editId = $editId;
        $this->resetValidation();
        $this->reset('form');
        if ($editId > 0) {
            $detail = ServiceDetail::findOrFail($editId);
            $this->form['reference'] = $detail->reference;
            $this->form['capacity'] = $detail->capacity;
            $this->form['quantity'] = $detail->quantity;
            $this->form['brand_id'] = $detail->brand_id;
            $this->form['component_id'] = $detail->component_id;
        } else {
            $this->form['reference'] = 'SIN REFERENCIA';
            $this->form['capacity'] = 'N/A';
            $this->form['quantity'] = 1;
        }

        $this->openModal = true;
    }

    public function render()
    {
        return view('livewire.admin.services.add-component');
    }

    protected $messages = [
        'form.component_id.required' => 'Debe escoger el componente.',
    ];

    public function save()
    {
        $this->validate([
            'form.component_id' => 'required|exists:components,id',
            'form.quantity' => 'required|integer|min:1',
            'form.brand_id' => 'nullable|exists:brands,id',
        ]);


        $service = Service::findOrFail($this->serviceId);
        if ($this->editId > 0) {
            $servicedetail = ServiceDetail::findOrFail($this->editId);
            $servicedetail->component_id = $this->form['component_id'];
            $servicedetail->brand_id = $this->form['brand_id'];
            $servicedetail->reference = $this->form['reference'] ?: 'SIN REFERENCIA';
            $servicedetail->capacity = $this->form['capacity'] ?: 'N/A';
            $servicedetail->quantity = $this->form['quantity'] ?? 1;
            $servicedetail->save();
            $this->emit('success', 'El componente fue editado correctamente.');
        } else {
            ServiceDetail::create([
                'service_id' => $service->id,
                'component_id' => $this->form['component_id'],
                'brand_id' => $this->form['brand_id'],
                'reference' => $this->form['reference'] ?: 'SIN REFERENCIA',
                'capacity' => $this->form['capacity'] ?: 'N/A',
                'quantity' => $this->form['quantity'] ?? 1,
            ]);
            $this->emit('success', 'El componente fue agregado correctamente.');
        }




        $this->emitTo('admin.services.edit', 'refreshServiceDetails');
        $this->openModal = false;
    }

    public function refreshComponents($newComponentId = null)
    {
        $this->components = ComponentModel::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        // Si se envió un ID válido, seleccionarlo automáticamente
        if ($newComponentId && isset($this->components[$newComponentId])) {
           $this->form['component_id'] = $newComponentId;
        }
    }

     public function refreshBrands($newBrandId = null)
    {
        $this->brands = Brand::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        // Si se envió un ID válido, seleccionarlo automáticamente
        if ($newBrandId && isset($this->brands[$newBrandId])) {
             $this->form['brand_id'] = $newBrandId;
        }
    }
}

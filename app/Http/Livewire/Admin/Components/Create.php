<?php

namespace App\Http\Livewire\Admin\Components;

use App\Models\Component as HardwareComponent;
use Livewire\Component;
use App\Traits\LivewireTrait;
use Livewire\WithPagination;

class Create extends Component
{
    use WithPagination;
    use LivewireTrait;

    public $openCreate = false;
    public $name;
    public $component_id;
    public $update = false;
    protected $listeners = ['openCreate'];

    public function openCreate()
    {
        $this->resetValidation();
        $this->reset('name');
        $this->openCreate = true;
    }

    public function render()
    {
        $components = HardwareComponent::latest()->paginate(10);

        return view('livewire.admin.components.create', compact('components'));
    }
    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:components,name',
        ]);

        $component = HardwareComponent::create([
            'name' => $this->name
        ]);

        // Emitir al editor de servicios o donde lo necesites
        $this->emitTo('admin.services.edit', 'refreshComponents', $component->id);
        $this->emitTo('admin.services.add-component', 'refreshComponents', $component->id);
        $this->emit('success', 'El componente fue registrado correctamente');


        $this->resetForm();
        $this->openCreate = false;
    }

    public function edit(HardwareComponent $component_type)
    {
        $this->component_id = $component_type->id;
        $this->name = $component_type->name;
        $this->update = true;
    }

    public function update()
    {

        $rules = [
            'name' => 'required|string|max:250|unique:brands,name,' . $this->component_id,
        ];

        $this->validate($rules);

        $brand_type = HardwareComponent::find($this->component_id);
        $brand_type->name = $this->name;
        $brand_type->save();

        $this->emit('success', 'Marca actualizada con éxito');
        $this->emitTo('admin.services.add-component', 'refreshComponents', $brand_type->id);
        $this->emitTo('admin.services.edit', 'refreshComponents', $brand_type->id);

        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset('name', 'update', 'component_id');
        $this->resetValidation();
    }

    public function cancel()
    {
        $this->resetForm();
    }
}

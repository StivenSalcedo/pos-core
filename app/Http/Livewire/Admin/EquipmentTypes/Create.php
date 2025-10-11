<?php

namespace App\Http\Livewire\Admin\EquipmentTypes;
use App\Models\EquipmentType;
use Livewire\Component;
use Livewire\WithPagination;

class Create extends Component
{

     use WithPagination;

    protected $listeners = ['openCreate'];

    public $openCreate = false, $update = false;

    public $equipment_type_id, $name;

    public $componentName='';

    protected $validationAttributes = ['name' => 'nombre'];

    public function render()
    {
        $equipmenttypes = EquipmentType::latest()->paginate(10);
        return view('livewire.admin.equipment-types.create', compact('equipmenttypes'));
    }
     public function openCreate($componentName)
    {
        $this->componentName = $componentName;
        $this->openCreate = true;
        $this->resetForm();
    }

    public function store()
    {

        $rules = [
            'name' => 'required|string|max:100|unique:equipment_types',
        ];

        $this->validate($rules);

        EquipmentType::create([
            'name' => $this->name
        ]);

        $this->emit('success', 'Tipo de equipo creado con éxito');

        $this->emitTo('admin.services.edit', 'refreshdata');

        $this->resetForm();
    }

    public function edit(EquipmentType $equipment_type)
    {
        $this->equipment_type_id = $equipment_type->id;
        $this->name = $equipment_type->name;
        $this->update = true;
    }

    public function update()
    {

        $rules = [
            'name' => 'required|string|max:100|unique:equipment_types,name,' . $this->equipment_type_id,
        ];

        $this->validate($rules);

        $equipment_type = EquipmentType::find($this->equipment_type_id);
        $equipment_type->name = $this->name;
        $equipment_type->save();

        $this->emit('success', 'Tipo de equipo actualizado con éxito');

        $this->emitTo('admin.services.edit', 'refreshdata');

        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset('name', 'update', 'equipment_type_id');
        $this->resetValidation();
    }

    public function cancel()
    {
        $this->resetForm();
    }

    protected function emitEventRefresh()
    {

    }
}

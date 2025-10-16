<?php

namespace App\Http\Livewire\Admin\Brands;

use Livewire\Component;
use App\Models\Brand;
use App\Traits\LivewireTrait;
use Livewire\WithPagination;

class Create extends Component
{
    use WithPagination;
    use LivewireTrait;

    public $openCreate = false;
    public $name;
    public $brand_id;
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
         $brands = Brand::latest()->paginate(10);
        return view('livewire.admin.brands.create',compact('brands'));
    }
    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:brands,name',
        ]);

        $brand = Brand::create([
            'name' => $this->name,
        ]);

        // Emitir al editor de servicios o donde lo necesites
        $this->emitTo('admin.services.edit', 'refreshBrands', $brand->id);
        $this->emit('success', 'La Marca fue registrada correctamente');
      

        $this->resetForm();
        $this->openCreate = false;
    }

    public function edit(Brand $brand_type)
    {
        $this->brand_id = $brand_type->id;
        $this->name = $brand_type->name;
        $this->update = true;
    }

    public function update()
    {

        $rules = [
            'name' => 'required|string|max:250|unique:brands,name,' . $this->brand_id,
        ];

        $this->validate($rules);

        $brand_type = Brand::find($this->brand_id);
        $brand_type->name = $this->name;
        $brand_type->save();

        $this->emit('success', 'Marca actualizada con éxito');

        $this->emitTo('admin.services.edit', 'refreshBrands', $brand_type->id);

        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset('name', 'update', 'brand_id');
        $this->resetValidation();
    }

    public function cancel()
    {
        $this->resetForm();
    }
}

<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    protected $listeners = ['openCreate'];

    public $openCreate = false, $update = false;

    public $category_id, $name;

    public $componentName='';

    protected $validationAttributes = ['name' => 'nombre'];

    public function render()
    {

        $categories = Category::latest()->paginate(10);

        return view('livewire.admin.categories.index', compact('categories'));
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
            'name' => 'required|string|max:100|unique:categories',
        ];

        $this->validate($rules);

        Category::create([
            'name' => $this->name
        ]);

        $this->emit('success', 'Categoría creada con éxito');

        $this->emitTo($this->componentName, 'refreshCategories');

        $this->resetForm();
    }

    public function edit(Category $category)
    {
        $this->category_id = $category->id;
        $this->name = $category->name;
        $this->update = true;
    }

    public function update()
    {

        $rules = [
            'name' => 'required|string|max:100|unique:categories,name,' . $this->category_id,
        ];

        $this->validate($rules);

        $category = Category::find($this->category_id);
        $category->name = $this->name;
        $category->save();

        $this->emit('success', 'Categoría creada con éxito');

        $this->emitTo($this->componentName, 'refreshCategories');

        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset('name', 'update', 'category_id');
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

<?php

namespace App\Http\Livewire\Admin\Providers;

use App\Enums\TypesProviders;
use App\Models\Provider;
use App\Rules\Identification;
use App\Rules\Phone;
use App\Traits\LivewireTrait;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Arr;

class Edit extends Component {

    use LivewireTrait;

    protected $listeners = ['openEdit'];

    public $openEdit=false, $types;

    public $provider;

    protected $validationAttributes=['no_identification' => 'NIT'];

    public function mount(){
        $this->types = TypesProviders::getCasesLabel();
        $this->provider = new Provider();
    }

    protected function rules(){
        return [
            'provider.no_identification' => ['required', 'integer', new Identification, 'unique:providers,no_identification,' . $this->provider->id],
            'provider.name' => 'required|string|min:5|max:250',
            'provider.direction' => 'nullable|string|max:250',
            'provider.phone' => ['nullable', 'string', new Phone],
            'provider.type' => ['nullable', 'string', Rule::in(TypesProviders::getCases())],
            'provider.description' => 'nullable|string|max:250',
            'provider.status' => 'required|integer|min:0|max:1',
            'provider.email' => 'nullable|string|email|max:250',
        ];
    }

    public function render() {
        return view('livewire.admin.providers.edit');
    }

    public function openEdit(Provider $provider){
        $this->provider = $provider;
        $this->resetValidation();
        $this->openEdit=true;
    }

    public function update(){

        $rules = Arr::except($this->rules(), ['provider.type']);
        $this->applyTrim(array_keys($rules));

        $this->validate();
        $this->provider->save();

        $this->emit('success', 'Proveedor actualizado con éxito');
        $this->emitTo('admin.providers.index', 'render');

        $this->resetExcept('types');

        $this->provider = new Provider();

        $this->openEdit = false;
    }
}

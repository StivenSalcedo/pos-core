<?php

namespace App\Http\Livewire\Admin\Providers;

use App\Enums\TypesProviders;
use App\Models\Provider;
use App\Rules\Identification;
use App\Rules\Phone;
use App\Traits\LivewireTrait;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{

    use LivewireTrait;

    protected $listeners = ['openCreate'];

    public $openCreate = false, $types;

    public $no_identification, $name, $direction, $phone, $type = '', $description, $email;

    public function mount()
    {
        $this->types = TypesProviders::getCasesLabel();
    }

    public function render()
    {
        return view('livewire.admin.providers.create');
    }

    public function openCreate()
    {
        $this->resetValidation();
        $this->openCreate = true;
    }

    public function store()
    {
        $rules = [
            'no_identification' => ['required', 'integer', new Identification, 'unique:providers'],
            'name' => 'required|string|min:5|max:250',
            'direction' => 'nullable|string|max:250',
            'phone' => ['nullable', 'string', new Phone],
            'type' => ['nullable', 'string', Rule::in(TypesProviders::getCases())],
            'description' => 'nullable|string|max:250',
            'email' => 'nullable|string|email|max:250',
        ];

        $this->applyTrim(['no_identification', 'name', 'direction', 'phone', 'type', 'description', 'email']);

        $data = $this->validate($rules, null, ['no_identification' => 'NIT']);

        $provider = Provider::create($data);
        $payload = $provider->only(['id', 'name', 'no_identification']);
        $this->emitTo('admin.products.edit',  'set-provider', $payload);
        $this->emitTo('admin.products.create',  'set-provider', $payload);
        $this->emit('success', 'Proveedor creado con éxito');
        $this->emitTo('admin.providers.index', 'render');
       

        $this->resetExcept('types');

        $this->openCreate = false;
    }
}

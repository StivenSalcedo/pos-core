<?php

namespace App\Http\Livewire\Admin\Staff;

use App\Models\Staff;
use App\Rules\Identification;
use App\Rules\Phone;
use App\Traits\LivewireTrait;
use Livewire\Component;

class Create extends Component {

    use LivewireTrait;

    protected $listeners = ['openCreate'];

    public $openCreate=false;

    public $no_identification, $names, $direction, $phone, $email, $description;

    public function render(){
        return view('livewire.admin.staff.create');
    }

    public function openCreate(){
        $this->resetValidation();
        $this->openCreate = true;
    }

    public function store(){
        $rules = [
            'no_identification' => ['required', 'integer', new Identification, 'unique:staff'],
            'names' => 'required|string|min:10|max:250',
            'direction' => 'nullable|string|min:5|max:250',
            'phone' => ['nullable', 'string', new Phone],
            'email' => 'nullable|string|email|max:250|unique:staff',
            'description' => 'nullable|string|max:250',
        ];

        $this->applyTrim(array_keys($rules));

        $data = $this->validate($rules);

        Staff::create($data);

        $this->emit('success', 'Empleado creado con éxito');
        $this->emitTo('admin.staff.index', 'render');

        $this->reset();
        $this->openCreate = false;

    }
}

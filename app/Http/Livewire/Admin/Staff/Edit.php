<?php

namespace App\Http\Livewire\Admin\Staff;

use App\Models\Staff;
use App\Rules\Identification;
use App\Rules\Phone;
use App\Traits\LivewireTrait;
use Livewire\Component;

class Edit extends Component {

    use LivewireTrait;

    protected $listeners=['openEdit'];

    public $openEdit=false, $staff;

    protected function rules(){
        return [
            'staff.no_identification' => ['required', 'integer', new Identification, 'unique:staff,no_identification,' . $this->staff->id],
            'staff.names' => 'required|string|min:10|max:250',
            'staff.direction' => 'nullable|string|min:5|max:250',
            'staff.phone' => ['nullable', 'string', new Phone],
            'staff.email' => 'nullable|string|email|max:250|unique:staff,email,' . $this->staff->id,
            'staff.description' => 'nullable|string|max:250',
            'staff.status' => 'required|integer|min:0|max:1',
        ];
    }

    public function mount(){
        $this->staff = new Staff();
    }


    public function render() {
        return view('livewire.admin.staff.edit');
    }

    public function openEdit(Staff $staff){
        $this->staff = $staff;
        $this->resetValidation();
        $this->openEdit=true;
    }

    public function update(){

        $this->applyTrim(array_keys($this->rules()));

        $this->validate();
        $this->staff->save();
        $this->staff = new Staff();

        $this->openEdit = false;

        $this->emitTo('admin.staff.index', 'render');
        $this->emit('success', 'Empleado actualizado con éxito');
    }
}

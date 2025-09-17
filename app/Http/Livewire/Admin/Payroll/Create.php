<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Models\Payroll;
use App\Models\Staff;
use Livewire\Component;

class Create extends Component {

    protected $listeners = ['openCreate', 'setStaff'];

    public $openCreate=false, $staff;

    public $price, $price_letters, $description, $staff_id;

    public function mount(){
        $this->staff = new Staff();
    }

    public function render() {
        return view('livewire.admin.payroll.create');
    }

    public function openCreate(){
        $this->resetExcept('staff');
        $this->resetValidation();
        $this->openCreate = true;
    }

    public function setStaff(Staff $staff){
        $this->staff = $staff;
        $this->staff_id = $staff->id;
    }

    public function store(){
        $rules = [
            'price' => 'required|integer|max:99999999',
            'description' => 'required|string|max:250',
            'staff_id' => 'required|exists:staff,id',
        ];

        $data = $this->validate($rules);
        $data['user_id'] = auth()->user()->id;

        Payroll::create($data);

        $this->reset();

        $this->staff = new Staff();

        $this->emitTo('admin.payroll.index', 'render');
        $this->emit('success', 'Pago registrado con éxito');
    }
}

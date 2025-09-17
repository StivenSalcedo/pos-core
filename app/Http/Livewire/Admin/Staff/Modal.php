<?php

namespace App\Http\Livewire\Admin\Staff;

use App\Models\Staff;
use Livewire\Component;
use Livewire\WithPagination;

class Modal extends Component {

    use WithPagination;

    protected $listeners = ['openModal'];

    public $openModal=false, $search, $filter='1';

    public $filters = [
        1 => 'Identificación',
        2 => 'Nombres'
    ];

    public function render() {

        $filter = [1 => 'no_identification',  2 => 'names'][$this->filter];

        $staff = Staff::where($filter, 'LIKE', '%' . $this->search . '%')
                    ->where('status', '0')
                    ->orderBy($filter, 'ASC')
                    ->paginate(10);
        return view('livewire.admin.staff.modal', compact('staff'));
    }

    public function openModal(){
        $this->reset();
        $this->openModal = true;
    }

    public function updatedSearch(){
        $this->resetPage();
    }

    public function updatedFilter(){
        $this->resetPage();
    }

    public function selected(Staff $staff){
        $this->emitTo('admin.payroll.create', 'setStaff', $staff->id);
        $this->reset();
    }
}

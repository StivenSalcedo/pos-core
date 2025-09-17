<?php

namespace App\Http\Livewire\Admin\Staff;

use App\Models\Staff;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component {

    use WithPagination;

    protected $listeners = ['render'];

    public $search, $filter='1';

    public $filters = [
        1 => 'Identificación',
        2 => 'Nombres'
    ];

    public function render() {

        $filter = [1 => 'no_identification',  2 => 'names'][$this->filter];

        $staff = Staff::where($filter, 'LIKE', '%' . $this->search . '%')
                    ->orderBy($filter, 'ASC')
                    ->paginate(10);

        return view('livewire.admin.staff.index', compact('staff'))->layoutData(['title' => 'Empleados']);
    }

    public function updatedSearch(){
        $this->resetPage();
    }

    public function updatedFilter(){
        $this->resetPage();
    }
}

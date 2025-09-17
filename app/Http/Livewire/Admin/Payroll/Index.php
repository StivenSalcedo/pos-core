<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Models\Payroll;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component {

    use WithPagination;

    protected $listeners = ['render'];

    public $search, $filter='1', $filterDate='0', $startDate, $endDate;

    public $filters = [
        1 => 'No Pago',
        2 => 'Identificación',
        3 => 'Nombre'
    ];

    public function render() {
        $filter = [1 => 'id',  2 => 'no_identification', 3 => 'names'][$this->filter];

        $total = Payroll::query()->search($filter, $this->search)
                        ->date($this->filterDate, $this->startDate, $this->endDate)
                        ->latest()
                        ->sum('price');

        $payroll = Payroll::query()
                    ->with('staff', 'user')
                    ->search($filter, $this->search)
                    ->date($this->filterDate, $this->startDate, $this->endDate)
                    ->latest()
                    ->paginate(10);


        return view('livewire.admin.payroll.index', compact('payroll', 'total'))->layoutData(['title' => 'Nominas']);
    }

    public function updatedSearch(){
        $this->resetPage();
    }

    public function updatedFilter(){
        $this->resetPage();
    }

    public function delete(Payroll $payroll){
        $payroll->delete();
        $this->emit('success', 'Pago eliminado con éxito');
    }
}

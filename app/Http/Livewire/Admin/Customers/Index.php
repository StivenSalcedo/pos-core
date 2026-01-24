<?php

namespace App\Http\Livewire\Admin\Customers;

use App\Exports\CustomersExport;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    protected $listeners = ['render'];

    public $search;

    public $filter = '1';

    public $filters = [
        1 => 'Identificación',
        2 => 'Nombres',
    ];

    public function mount()
    {
       
    }
    public function render()
    {

        $filter = [1 => 'no_identification',  2 => 'names'][$this->filter];

        $customers = Customer::with('identificationDocument')
            ->where($filter, 'LIKE', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.customers.index', compact('customers'))->layoutData(['title' => 'Clientes']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function exportCustomers()
    {
        return Excel::download(new CustomersExport(), 'Clientes.xlsx');
    }
}

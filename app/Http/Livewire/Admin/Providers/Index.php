<?php

namespace App\Http\Livewire\Admin\Providers;

use App\Exports\ProvidersExport;
use App\Models\Provider;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component {

    use WithPagination;

    protected $listeners = ['render'];

    public $search, $filter='1';

    public $filters = [
        1 => 'NIT',
        2 => 'Nombre'
    ];

    public function render() {
        $filter = [1 => 'no_identification',  2 => 'name'][$this->filter];

        $providers = Provider::where($filter, 'LIKE', '%' . $this->search . '%')
                    ->latest()
                    ->paginate(10);

        return view('livewire.admin.providers.index', compact('providers'))->layoutData(['title' => 'Proveedores']);
    }

    public function updatedSearch(){
        $this->resetPage();
    }

    public function updatedFilter(){
        $this->resetPage();
    }

    public function exportProviders(){
        return Excel::download(new ProvidersExport(), 'Proveedores.xlsx');
    }
}

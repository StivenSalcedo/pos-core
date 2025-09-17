<?php

namespace App\Http\Livewire\Admin\Providers;

use App\Models\Provider;
use Livewire\Component;
use Livewire\WithPagination;

class Modal extends Component {

    use WithPagination;

    protected $listeners = ['openModal'];

    public $openModal=false, $search, $filter='1';

    public $filters = [
        1 => 'N° Identificación',
        2 => 'Nombres'
    ];

    public function render() {

        $filter = [1 => 'no_identification',  2 => 'name'][$this->filter];

        $providers = Provider::where($filter, 'LIKE', '%' . $this->search . '%')
                    ->where('status', '0')
                    ->orderBy($filter, 'ASC')
                    ->paginate(10);

        return view('livewire.admin.providers.modal', compact('providers'));
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

    public function selected(Provider $provider){
        $this->emitTo('admin.purchases.create', 'setProvider', $provider->id);
        $this->reset();
    }
}

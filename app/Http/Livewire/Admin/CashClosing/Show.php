<?php

namespace App\Http\Livewire\Admin\CashClosing;

use App\Models\CashClosing;
use Livewire\Component;

class Show extends Component {

    protected $listeners=['openShow'];

    public $openShow=false;

    public $cashClosing;

    public function mount(){
        $this->cashClosing = new CashClosing();
    }

    public function render() {
        return view('livewire.admin.cash-closing.show');
    }

    public function openShow(CashClosing $cashClosing){
        $this->openShow = true;
        $this->cashClosing = $cashClosing;
    }
}

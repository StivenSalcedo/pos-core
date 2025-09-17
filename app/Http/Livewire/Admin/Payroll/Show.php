<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Http\Controllers\Admin\PayrollController;
use App\Models\Payroll;
use Livewire\Component;

class Show extends Component {

    protected $listeners = ['openShow'];

    public $openShow=false, $payroll;

    public function mount(){
        $this->payroll = new Payroll();
    }

    public function render() {
        return view('livewire.admin.payroll.show');
    }

    public function openShow(Payroll $payroll){
        $this->payroll = $payroll;
        $this->payroll->price = '$ ' . number_format($this->payroll->price, 0, '.', ',');
        $this->openShow = true;
    }

    public function download(){
        return redirect()->route('admin.payroll.download', $this->payroll->id);
    }
}

<?php

namespace App\Http\Livewire\Admin\Bills;

use App\Models\Bill;
use Livewire\Component;

class Show extends Component
{

    public $bill;

    public function mount(Bill $bill)
    {
        $this->bill = $bill;
    }

    public function render()
    {
        return view('livewire.admin.bills.show')->layoutData(['title' => 'Factura N° ' . $this->bill->id]);
    }
}

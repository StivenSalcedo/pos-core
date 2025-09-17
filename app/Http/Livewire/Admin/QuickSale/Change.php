<?php

namespace App\Http\Livewire\Admin\QuickSale;

use App\Models\PaymentMethod;
use Livewire\Component;

class Change extends Component
{
    public $paymentMethods = [];

    public function mount()
    {
        $paymentMethods = PaymentMethod::where('status', PaymentMethod::ACTIVE)->get();
        $this->paymentMethods = $paymentMethods->pluck('name', 'id');
    }

    public function render()
    {
        return view('livewire.admin.quick-sale.change');
    }
}

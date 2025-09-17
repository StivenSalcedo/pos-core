<?php

namespace App\Http\Livewire\Admin\QuickSale;

use App\Models\Customer;
use Livewire\Component;

class Customers extends Component
{

    public $customers;

    public $openModal=false;

    public function render()
    {

        $this->customers = Customer::where('status', '0')
                        ->select(['id', 'no_identification', 'names', 'phone'])
                        ->orderBy('top', 'ASC')
                        ->get()
                        ->toArray();

        return view('livewire.admin.quick-sale.customers');
    }
}

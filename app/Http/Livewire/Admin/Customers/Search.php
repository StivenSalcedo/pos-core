<?php

namespace App\Http\Livewire\Admin\Customers;

use App\Models\Customer;
use Livewire\Component;

class Search extends Component {

    public $customers;

    public function render() {
        
        $this->customers = Customer::where('status', '0')
                        ->select(['id', 'no_identification', 'names', 'phone'])
                        ->orderBy('top', 'ASC')
                        ->get()
                        ->toArray();

        return view('livewire.admin.customers.search');
    }
}

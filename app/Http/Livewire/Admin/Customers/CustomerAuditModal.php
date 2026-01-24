<?php

namespace App\Http\Livewire\Admin\Customers;

use Livewire\Component;
use App\Models\Customer;
use App\Traits\LivewireTrait;

class CustomerAuditModal extends Component
{
    use LivewireTrait;
    public ?Customer $customer = null;
    public bool $open1 = false;


    protected $listeners = ['open1'];

    public function open1(int $customerId)
    {
        $this->customer = Customer::with('audits.user')->findOrFail($customerId);
        $this->open1 = true;
    }
    public function render()
    {
        return view('livewire.admin.customers.customer-audit-modal');
    }
}

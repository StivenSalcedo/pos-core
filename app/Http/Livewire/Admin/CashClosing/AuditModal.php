<?php

namespace App\Http\Livewire\Admin\CashClosing;

use Livewire\Component;
use App\Models\CashClosing;
use App\Traits\LivewireTrait;

class AuditModal extends Component
{

 use LivewireTrait;
    public ?CashClosing $customer = null;
    public bool $open = false;


    protected $listeners = ['open'];

    public function open(int $CashClosingId)
    {
        $this->customer = CashClosing::with('audits.user')->findOrFail($CashClosingId);
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.admin.cash-closing.audit-modal');
    }
}

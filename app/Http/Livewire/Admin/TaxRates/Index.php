<?php

namespace App\Http\Livewire\Admin\TaxRates;

use App\Models\TaxRate;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render()
    {
        $taxRates = TaxRate::latest()->orderBy('tribute_id')->get();
        return view('livewire.admin.tax-rates.index', compact('taxRates'))->layoutData(['title' => 'Impuestos']);
    }
}

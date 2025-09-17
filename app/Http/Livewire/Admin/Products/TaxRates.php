<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\TaxRate;
use Illuminate\Support\Collection;
use Livewire\Component;

class TaxRates extends Component
{
    protected $listeners = ['openModal'];

    public bool $show = false;
    public array $formatTaxRates;
    public array $taxRates;
    public string $nameComponent;
    public string $selectedTax;
    public Collection $selectedTaxes;
    public array $tax = [];
    public $milliliter = 0;

    public function mount()
    {
        $this->taxRates = TaxRate::with('tribute')
            ->enabled()
            ->get(['id', 'name', 'rate', 'has_percentage', 'tribute_id'])
            ->append(['format_rate', 'format_name', 'format_name2'])
            ->map(fn ($item) => $item->only(['id', 'name', 'rate', 'has_percentage', 'format_rate', 'format_name', 'format_name2']))
            ->toArray();

        $this->formatTaxRates = collect($this->taxRates)->pluck('format_name2', 'id')->toArray();

        $this->selectedTaxes = collect();
    }

    public function render()
    {
        return view('livewire.admin.products.tax-rates');
    }

    public function updatedSelectedTax($value)
    {
        if ($value) {
            $this->tax = collect($this->taxRates)->firstWhere('id', $value);
            $this->tax['value'] = 0;
            $this->milliliter = 0;
        } else {
            $this->tax = [];
        }
    }

    public function openModal($data)
    {
        $this->nameComponent = $data['nameComponent'];

        $this->selectedTaxes = collect($data['taxRates']);

        $this->show = true;
    }

    public function add()
    {
        $this->validate(['milliliter' => 'required|integer|max:999999999'], [], ['milliliter' => 'milimetros del producto']);

        $this->tax['value'] = (int)($this->milliliter / 100) * (int) $this->tax['rate'];

        $this->selectedTaxes->push($this->tax);
        $this->tax = [];
        $this->selectedTax = '';
        $this->milliliter = 0;
        $this->setTaxRates();
    }

    public function remove($key)
    {
        $this->selectedTaxes->forget($key);
        $this->setTaxRates();
    }

    protected function setTaxRates()
    {
        if ($this->nameComponent === 'admin.products.edit') {
            $this->emitTo('admin.products.edit', 'setTaxRates', $this->selectedTaxes);
        } else {
            $this->emitTo('admin.products.create', 'setTaxRates', $this->selectedTaxes);
        }
    }
}

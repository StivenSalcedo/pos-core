<?php

namespace App\Http\Livewire\Admin\TaxRates;

use App\Models\TaxRate;
use App\Models\Tribute;
use Livewire\Component;

class Edit extends Component
{
    protected $listeners = ['open-modal' => 'openModal'];

    public $openModal = false;

    public $tributes;

    public $taxRate;

    public function mount()
    {
        $this->taxRate = new TaxRate();
        $this->tributes = Tribute::enabled()->get()->pluck('name', 'id');
    }

    protected function rules()
    {
        return [
            'taxRate.tribute_id' => 'required|exists:tributes,id',
            'taxRate.name' => 'required|string|max:100',
            'taxRate.has_percentage' => 'required|boolean',
            'taxRate.rate' => 'required|integer|min:0|max:100',
            'taxRate.status' => 'required|integer|min:0|max:1',
        ];
    }

    public function render()
    {
        return view('livewire.admin.tax-rates.edit');
    }

    public function openModal(TaxRate $taxRate)
    {
        // estas tazas de interes no se pueden modificar
        if ($taxRate->id === 1 || $taxRate->id === 9) {
            return;
        }

        $this->resetValidation();
        $this->resetExcept('tributes');
        $this->taxRate = $taxRate;
        $this->taxRate->rate = (int) $taxRate->rate;
        $this->openModal = true;
    }

    public function update()
    {
        $data = $this->validate();

        // estas tazas de interes no se pueden modificar
        if ($this->taxRate->id === 1 || $this->taxRate->id === 9) {
            return;
        }

        $this->taxRate->fill($data);
        $this->taxRate->save();

        $this->reset('openModal');
        $this->emitTo('admin.tax-rates.index', 'render');
        $this->emit('success', 'Impuesto actualizado con éxito');
    }
}

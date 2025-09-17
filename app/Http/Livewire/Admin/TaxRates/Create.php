<?php

namespace App\Http\Livewire\Admin\TaxRates;

use App\Models\TaxRate;
use App\Models\Tribute;
use Livewire\Component;

class Create extends Component
{
    protected $listeners = ['open-modal' => 'openModal'];

    public $openModal = false;

    public $tributes;

    public $name, $has_percentage = 1, $rate=0, $tribute_id;

    public function mount()
    {
        $this->tributes = Tribute::enabled()->get()->pluck('name', 'id');
    }

    protected function rules()
    {
        return [
            'tribute_id' => 'required|exists:tributes,id',
            'name' => 'required|string|max:100',
            'has_percentage' => 'required|boolean',
            'rate' => 'required|integer|min:0|max:100',
        ];
    }

    public function render()
    {
        return view('livewire.admin.tax-rates.create');
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->resetExcept('tributes');
        $this->openModal = true;
    }

    public function store()
    {
        $data = $this->validate();

        TaxRate::create($data);

        $this->reset('openModal');
        $this->emitTo('admin.tax-rates.index', 'render');
        $this->emit('success', 'Impuesto creado con éxito');
    }
}

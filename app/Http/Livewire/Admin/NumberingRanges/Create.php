<?php

namespace App\Http\Livewire\Admin\NumberingRanges;

use App\Models\NumberingRange;
use App\Traits\LivewireTrait;
use Livewire\Component;

class Create extends Component {

    use LivewireTrait;

    protected $listeners = ['openCreate'];

    public $openCreate = false;

    public $prefix, $from, $to, $current, $resolution_number, $date_authorization, $expire;

    public function render() {
        return view('livewire.admin.numbering-ranges.create');
    }

    public function openCreate(){
        $this->resetValidation();
        $this->reset();
        $this->openCreate = true;
    }

    public function store(){

        $rules = [
            'prefix' => 'required|string|min:1|max:4',
            'from' => 'required|integer|min:1|max:999999999999999',
            'to' => 'required|integer|min:1|max:999999999999999',
            'current' => 'required|integer|min:1|max:999999999999999',
            'resolution_number' => 'nullable|max:250',
            'date_authorization' => 'required|date',
            'expire' => 'required|date',
        ];

        $attributes = [
            'current' => 'actual',
            'date_authorization' => 'fecha de autorización',
            'expire' => 'fecha de vencimiento',
        ];

        $this->applyTrim(array_keys($rules));

        $data = $this->validate($rules, null, $attributes);

        if ($this->from >= $this->to) $this->emit('alert', "El campo 'desde' no deber ser superior al campo 'hasta'");

        if ($this->current < $this->from || $this->current > $this->to) {
            return $this->emit('alert', "El campo 'actual' no deber ser inferior al campo 'desde' o superior al campo 'hasta' ");
        }

        NumberingRange::create($data);

        $this->reset();

        $this->emitTo('admin.numbering-ranges.index', 'render');
        $this->emit('success', 'Rango de numeración creado con éxito');

    }

}

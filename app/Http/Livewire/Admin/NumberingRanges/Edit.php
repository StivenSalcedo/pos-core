<?php

namespace App\Http\Livewire\Admin\NumberingRanges;

use App\Models\NumberingRange;
use App\Traits\LivewireTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component {

    use LivewireTrait;

    protected $listeners = ['openEdit'];

    public $openEdit=false, $range, $date_authorization, $expire;

    protected $validationAttributes = [
        'current' => 'actual',
        'date_authorization' => 'fecha de autorización',
        'expire' => 'fecha de vencimiento',
    ];

    public function mount(){
        $this->range = new NumberingRange();
    }

    protected function rules(){
        return [
            'range.prefix' => 'required|string|min:1|max:4',
            'range.from' => 'required|integer|min:1|max:999999999999999',
            'range.to' => 'required|integer|min:1|max:999999999999999',
            'range.current' => 'required|integer|min:1|max:999999999999999',
            'range.resolution_number' => 'nullable|max:250',
            'range.status' => 'required|integer|min:0|max:1',
            'date_authorization' => 'required|date',
            'expire' => 'required|date',
        ];
    }

    public function render() {
        return view('livewire.admin.numbering-ranges.edit');
    }

    public function openEdit(NumberingRange $range){
        $this->resetValidation();
        $this->reset();
        $this->openEdit = true;
        $this->range = $range;
        $this->expire = $range->expire->format('Y-m-d');
        $this->date_authorization = $range->date_authorization->format('Y-m-d');
    }

    public function update(){

        $this->applyTrim(array_keys($this->rules()));

        $this->validate();

        if ($this->range->from >= $this->range->to) $this->emit('alert', "El campo 'desde' no deber ser superior al campo 'hasta'");

        if ($this->range->current < $this->range->from || $this->range->current > $this->range->to) {
            return $this->emit('alert', "El campo 'actual' no deber ser inferior al campo 'desde' o superior al campo 'hasta' ");
        }

        $this->range->date_authorization = $this->date_authorization;
        $this->range->expire = $this->expire;

        $this->range->save();

        $this->reset();

        $this->range = new NumberingRange();

        $this->emitTo('admin.numbering-ranges.index', 'render');
        $this->emit('success', 'Rango de numeración creado con éxito');

    }
}

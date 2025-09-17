<?php

namespace App\Http\Livewire\Admin\Outputs;

use App\Enums\CashRegisters;
use App\Models\Output;
use App\Rules\Identification;
use App\Rules\Phone;
use App\Traits\LivewireTrait;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component {

    use LivewireTrait;

    protected $listeners = ['openCreate'];

    public $openCreate=false, $cashRegisters;

    public $reason, $from='', $date, $price, $description;

    public function mount(){
        $this->date = now()->format('Y-m-d');
        $this->cashRegisters = CashRegisters::getCasesLabel();
    }

    public function render() {
        return view('livewire.admin.outputs.create');
    }

    public function openCreate(){
        $this->resetValidation();
        $this->openCreate = true;
    }

    public function store(){
        $rules = [
            'reason' => 'required|string|min:5|max:250',
            'from' => ['required', Rule::in(CashRegisters::getCases())],
            'date' => 'required|date',
            'price' => 'nullable|integer|max:99999999',
            'description' => 'nullable|string|max:250',
        ];

        $attributes = [
            'reason' => 'motivo',
            'from' => 'caja',
            'price'  => 'valor',
        ];

        $this->applyTrim(array_keys($rules));

        $data = $this->validate($rules, null, $attributes);
        $data['user_id'] = auth()->user()->id;
        
        $this->validateTerminal();

        $data['terminal_id'] = getTerminal()->id;
        
        Output::create($data);

        $this->emit('success', 'Egreso agregado con éxito');
        $this->emitTo('admin.outputs.index', 'render');

        $this->resetExcept('cashRegisters');

        $this->date = now()->format('Y-m-d');
    }
}

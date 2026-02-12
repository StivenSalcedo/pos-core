<?php

namespace App\Http\Livewire\Admin\Outputs;

use App\Enums\CashRegisters;
use App\Models\Output;
use App\Rules\Identification;
use App\Rules\Phone;
use App\Traits\LivewireTrait;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Terminal;
use Carbon\Carbon;

class Edit extends Component
{

    use LivewireTrait, AuthorizesRequests;

    protected $listeners = ['openEdit'];

    public $openEdit = false, $output, $cashRegisters;

    public $date;
    public $terminals;
    public $terminal_id = '';
    public function mount()
    {
        $this->terminals = Terminal::all()->pluck('name', 'id');
        $this->output = new Output();
        $this->cashRegisters = CashRegisters::getCasesLabel();
    }

    public function render()
    {
        return view('livewire.admin.outputs.edit');
    }

    protected function rules()
    {
        $rules = [
            'output.reason' => 'required|string|min:5|max:250',
            'date' => 'required|date',
            'output.price' => 'nullable|integer|max:99999999',
            'output.description' => 'nullable|string|max:250',
        ];

        if (auth()->user()->can('ver todas las sedes')) {
            $rules['output.terminal_id'] = 'required|exists:terminals,id';
        }

        return $rules;
    }

    public function openEdit(Output $output)
    {
        $this->resetValidation();
        $this->openEdit = true;
        $this->output = $output;
        $this->terminal_id = $output->terminal_id == null ? '' : $output->terminal_id;
        $this->date = $output->date->format('Y-m-d');
    }

    public function update()
    {

        $this->authorize('isAccounted', $this->output);

        $rules = Arr::except($this->rules(), 'output.from');

        $this->applyTrim(array_keys($rules));
        $this->validate();


        $this->output->date = Carbon::parse($this->date)->startOfDay();
        $this->output->save();

        $this->emit('success', 'Egreso actualizado con éxito');
        $this->emitTo('admin.outputs.index', 'render');

        $this->resetExcept('cashRegisters', 'terminals');
        $this->output = new Output();
    }
}

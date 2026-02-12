<?php

namespace App\Http\Livewire\Admin\CashClosing;

use App\Enums\CashRegisters;
use App\Models\Bill;
use App\Models\CashClosing;
use App\Models\DetailFinance;
use App\Models\Output;
use App\Models\PaymentMethod;
use App\Models\Terminal;
use App\Traits\LivewireTrait;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Http\Livewire\Admin\CashClosing\CashClosingCalculator;

class Create extends Component
{

    use LivewireTrait;

    protected $listeners = ['openCreate'];

    public $openCreate = false;

    public $bills, $lastRecord, $terminal;

    public $cash, $credit_card, $debit_card, $transfer, $tip, $outputs, $cashRegister = 0, $base, $price, $total_sales, $observations;

    public $closing_date;
    public $isFuture = false;
    public $terminals;
    public $terminal_id = '';


    public function mount()
    {
        $this->terminal = new Terminal();
    }



    public function render()
    {

        return view('livewire.admin.cash-closing.create');
    }

    public function updatedTerminalId($value)
    {
        if (!$this->closing_date) {
            return;
        }

        $this->terminal = Terminal::find($value);

        $this->loadCalculatedValues();
    }

    public function updatedClosingDate($value)
    {
        $date = Carbon::parse($value)->startOfDay();
        $this->terminal = Terminal::findOrFail($this->terminal_id);
        if ($date->isPast() && !$date->isToday()) {
            $this->emit('alert', 'No puedes seleccionar fechas pasadas');
            $this->closing_date = now()->toDateString();
            return;
        }
        $this->isFuture = $date->isFuture();
        $this->loadCalculatedValues();
    }

    public function loadCalculatedValues()
    {
        $calculator = new CashClosingCalculator();

        $data = $calculator->calculate($this->terminal, Carbon::parse($this->closing_date));

        $this->fill($data);
        $previousClosing = CashClosing::where('terminal_id', $this->terminal->id)
            ->whereDate('closing_date', Carbon::parse($this->closing_date))
            ->first();
        if ($previousClosing) {
            $this->base = $previousClosing->base;
            $this->price = $previousClosing->price;
            $this->observations = $previousClosing->observations;
            $this->getCashRegister();
        }
    }


    public function updatedBase()
    {
        $this->getCashRegister();
    }

    public function openCreate()
    {
        $this->terminal_id = auth()->user()->terminals->first()->id;
        $this->terminals = Terminal::all()->pluck('name', 'id');
        if (auth()->user()->can('ver todas las sedes')) {
            $this->terminal = Terminal::findOrFail($this->terminal_id);
        } else {
            $this->terminal = Terminal::findOrFail(auth()->user()->terminals->first()->id);
        }
        $this->openCreate = true;
        $this->closing_date = now()->toDateString();
        $this->loadCalculatedValues();
        $this->isFuture = Carbon::parse($this->closing_date)->isFuture();
    }






    private function getCashRegister()
    {

        if (is_numeric($this->base)) return $this->cashRegister = ($this->cash + $this->base) - intval($this->outputs);
        $this->cashRegister = $this->cash - intval($this->outputs);
    }



    public function store()
    {

        $rules = [
            'base' => 'required|integer|min:0|max:99999999',
            'price' => 'required|integer|min:0|max:99999999',
            'observations' => 'nullable|string|max:255',
            'terminal_id' => 'required|exists:terminals,id'
        ];

        $attributes = [
            'base' => 'base inicial',
            'price' => 'dinero real en caja',
            'observations' => 'observaciones',
            'terminal_id' => 'Sede'
        ];

        $this->validateTerminal();

        if (Carbon::parse($this->closing_date)->isFuture()) {
            // Solo base
            CashClosing::create([
                'closing_date' => $this->closing_date,
                'base' => $this->base,
                'terminal_id' => $this->terminal_id,
                'user_id' => auth()->id(),
                'price' => '0',
                'cash' => '0',
                'debit_card' => '0',
                'credit_card' => '0',
                'transfer' => '0',
                'total_sales' => '0',
                'tip' => '0',
                'outputs' => '0',
                'cash_register' => '0',

            ]);

            $this->emit('success', 'Base creada para cierre futuro');
            $this->reset();
            $this->terminal = new Terminal();
            $this->emitTo('admin.cash-closing.index', 'render');
        }
        // HOY → cálculo completo
        $calculator = new CashClosingCalculator();

        $data = $calculator->calculate($this->terminal, Carbon::parse($this->closing_date));

        if (($data['cash_register'] ?? 0) < 0) {
            return $this->emit('alert', 'El dinero esperado en caja no puede ser negativo');
        }

        $this->validate($rules, null, $attributes);

        /* if ($this->cashRegister < 0) {
            return $this->emit('alert', 'El dinero esperado en caja no puede ser negativo');
        }*/

        //$this->getData();

        $previousClosing = CashClosing::where('terminal_id', $this->terminal->id)
            ->whereDate('closing_date', Carbon::parse($this->closing_date))
            ->first();
        if ($previousClosing) {
            $previousClosing->update([
                ...$data,
                'base' => $this->base,
                'price' => $this->price,
                'observations' => $this->observations,
            ]);
        } else {

            CashClosing::create([
                ...$data,
                'closing_date' => $this->closing_date,
                'base' => $this->base,
                'price' => $this->price,
                'observations' => $this->observations,
                'terminal_id' => $this->terminal->id,
                'user_id' => auth()->id(),
            ]);
        }



        /* CashClosing::create([
            'base' => $this->base,
            'cash' => $this->cash,
            'debit_card' => $this->debit_card,
            'credit_card' => $this->credit_card,
            'transfer' => $this->transfer,
            'total_sales' => $this->total_sales,
            'tip' => $this->tip,
            'outputs' => $this->outputs,
            'cash_register' => $this->cashRegister,
            'price' => $this->price,
            'observations' => $this->observations,
            'user_id' => auth()->user()->id,
            'terminal_id' => $this->terminal->id,
        ]);*/

        $this->reset();
        $this->terminal = new Terminal();
        $this->emitTo('admin.cash-closing.index', 'render');
        $this->emit('success', 'Cierre de caja realizado con éxito');
    }
}

<?php

namespace App\Http\Livewire\Admin\CashClosing;

use App\Models\CashClosing;
use App\Http\Livewire\Admin\CashClosing\CashClosingCalculator;
use Livewire\Component;
use App\Models\Terminal;
use Carbon\Carbon;

class Show extends Component
{
    protected $listeners = ['openShow'];

    public bool $openShow = false;
    public bool $isConfirmed = false;
    public bool $isEdited = false;

    public CashClosing $cashClosing;

    // Campos editables
    public $cash;
    public $credit_card;
    public $debit_card;
    public $transfer;
    public $base;
    public $price;
    public $observations;
    public $cashRegister;
    public $outputs;
    public $tip;
    public $total_sales;

    public function mount()
    {
        $this->cashClosing = new CashClosing();
    }

    public function openShow(CashClosing $cashClosing)
    {
        $user = auth()->user();
        $this->openShow = true;
        $this->cashClosing = $cashClosing;
        $this->isConfirmed = !is_null($cashClosing->confirmed_at);


        if ($user->can('Editar cierre de caja') || $this->isConfirmed) {
            $this->isEdited = true;
        } else {
            $this->isEdited = false;
        }


        if ($this->isConfirmed) {
            // 👉 usar valores guardados
            $this->fillFromModel();
        } else {
            // 👉 recalcular + mezclar con lo guardado
            $this->loadCalculatedValues();
        }
    }

    protected function fillFromModel()
    {
        $this->cash = $this->cashClosing->cash;
        $this->credit_card = $this->cashClosing->credit_card;
        $this->debit_card = $this->cashClosing->debit_card;
        $this->transfer = $this->cashClosing->transfer;
        $this->base = $this->cashClosing->base;
        $this->price = $this->cashClosing->price;
        $this->observations = $this->cashClosing->observations;
        $this->outputs = $this->cashClosing->outputs;
        $this->tip = $this->cashClosing->tip;
        $this->total_sales = $this->cashClosing->total_sales;

        if (is_numeric($this->base)) {
            $this->cashRegister = ($this->cash + $this->base) - intval($this->outputs);
        } else {
            $this->cashRegister = $this->cash - intval($this->outputs);
        }
    }

    protected function loadCalculatedValues()
    {
        $terminal = Terminal::findOrFail($this->cashClosing->terminal_id);
        $calculator = app(CashClosingCalculator::class);
        $date = Carbon::parse($this->cashClosing->closing_date);

        $calculated = $calculator->calculate(
            $terminal,
            $date,
            null
        );

        $this->cash = $calculated['cash'];
        $this->credit_card = $calculated['credit_card'];
        $this->debit_card = $calculated['debit_card'];
        $this->transfer = $calculated['transfer'];
        $this->outputs = $calculated['outputs'];
        $this->tip = $calculated['tip'];
        $this->total_sales = $calculated['total_sales'];

        // mantener base y observaciones ya guardadas
        $this->base = $this->cashClosing->base;
        $this->price = $this->cashClosing->price;
        $this->observations = $this->cashClosing->observations;
        $this->getCashRegister();
    }

    public function updatedBase()
    {
        $this->getCashRegister();
    }


    private function getCashRegister()
    {
        if (is_numeric($this->base)) {
            $this->cashRegister = ($this->cash + $this->base) - intval($this->outputs);
        } else {
            $this->cashRegister = $this->cash - intval($this->outputs);
        }
    }

    public function save()
    {
        $this->cashClosing->update([
            'cash' => $this->cash,
            'credit_card' => $this->credit_card,
            'debit_card' => $this->debit_card,
            'transfer' => $this->transfer,
            'base' => $this->base,
            'price' => $this->price,
            'observations' => $this->observations,
        ]);

        if (!$this->isConfirmed) {
            $this->cashClosing->update([
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
            ]);
        }

        $this->emitTo('admin.cash-closing.index', 'render');
        $this->openShow = false;
    }

    public function render()
    {
        return view('livewire.admin.cash-closing.show');
    }
}

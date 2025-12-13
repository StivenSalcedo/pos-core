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

class Create extends Component
{

    use LivewireTrait;

    protected $listeners = ['openCreate'];

    public $openCreate = false;

    public $bills, $lastRecord, $terminal;

    public $cash, $credit_card, $debit_card, $transfer, $tip, $outputs, $cashRegister, $base, $price, $total_sales, $observations;

    public function mount()
    {
        $this->terminal = new Terminal();
    }

    public function render()
    {
        return view('livewire.admin.cash-closing.create');
    }

    public function updatedBase()
    {
        $this->getCashRegister();
    }

    public function openCreate(Terminal $terminal)
    {
        $this->terminal = $terminal;
        $this->openCreate = true;
        $this->getData();
    }

    private function getData()
    {
        $this->lastRecord = CashClosing::latest('id')->where('terminal_id', $this->terminal->id)->first();
        $this->getBills();
        $this->getFinances();
        $this->getOutputs();
        $this->getCashRegister();
    }

    private function getBills()
    {

        if ($this->lastRecord) {
            $this->bills = Bill::where('created_at', '>', $this->lastRecord->created_at)
                ->where('terminal_id', $this->terminal->id)
                ->where('status', Bill::ACTIVA)
                ->doesntHave('finance')
                ->select('id', 'tip', 'total', 'payment_method_id')
                ->get();
        } else {

            $this->bills = Bill::where('status', Bill::ACTIVA)
                ->where('terminal_id', $this->terminal->id)
                ->select('id', 'tip', 'total', 'payment_method_id')
                ->doesntHave('finance')
                ->get();
        }

        if ($this->bills) {
            $this->cash         = $this->bills->where('payment_method_id', PaymentMethod::CASH)->sum('total');
            $this->credit_card  = $this->bills->where('payment_method_id', PaymentMethod::CREDIT_CARD)->sum('total') + $this->bills->where('payment_method_id', PaymentMethod::CREDIT_CARD)->sum('tip');
            $this->debit_card   = $this->bills->where('payment_method_id', PaymentMethod::DEBIT_CARD)->sum('total') + $this->bills->where('payment_method_id', PaymentMethod::DEBIT_CARD)->sum('tip');
            $this->transfer     = $this->bills->where('payment_method_id','>=', PaymentMethod::TRANSFER)->sum('total') + $this->bills->where('payment_method_id','>=', PaymentMethod::TRANSFER)->sum('tip');
            $this->tip          = $this->bills->sum('tip');
        }
    }

    private function getFinances()
    {

        $query = DetailFinance::query()->where('terminal_id', $this->terminal->id);

        if ($this->lastRecord) {
            $query->where('created_at', '>', $this->lastRecord->created_at);
        } else {
            $query->whereRelation('bill', 'bills.status', Bill::ACTIVA);
        }

        $finances = $query->get();

        $this->cash         = $this->cash + $finances->where('payment_method_id', PaymentMethod::CASH)->sum('value');
        $this->credit_card  = $this->credit_card + $finances->where('payment_method_id', PaymentMethod::CREDIT_CARD)->sum('value');
        $this->debit_card   = $this->debit_card + $finances->where('payment_method_id', PaymentMethod::DEBIT_CARD)->sum('value');
        $this->transfer     = $this->transfer + $finances->where('payment_method_id', '>=',PaymentMethod::TRANSFER)->sum('value');

        $this->total_sales = $this->cash + $this->credit_card + $this->debit_card + $this->transfer;
    }

    private function getCashRegister()
    {

        if (is_numeric($this->base)) return $this->cashRegister = ($this->cash + $this->base) - intval($this->outputs);
        $this->cashRegister = $this->cash - intval($this->outputs);
    }

    private function getOutputs()
    {
        if ($this->lastRecord) {
            $this->outputs = Output::where('created_at', '>', $this->lastRecord->created_at)
                ->where('terminal_id', $this->terminal->id)
                ->where('from', CashRegisters::MAIN)
                ->select('id', 'value')
                ->sum('price');
        } else {
            $this->outputs = Output::select('id', 'value')
                ->where('terminal_id', $this->terminal->id)
                ->where('from', CashRegisters::MAIN)
                ->sum('price');
        }
    }

    public function store()
    {

        $rules = [
            'base' => 'required|integer|min:0|max:99999999',
            'price' => 'required|integer|min:0|max:99999999',
            'observations' => 'nullable|string|max:255'
        ];

        $attributes = [
            'base' => 'base inicial',
            'price' => 'dinero real en caja',
            'observations' => 'observaciones'
        ];

        $this->validateTerminal();

        $this->validate($rules, null, $attributes);

        if ($this->cashRegister < 0) {
            return $this->emit('alert', 'El dinero esperado en caja no puede ser negativo');
        }

        $this->getData();

        CashClosing::create([
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
        ]);

        $this->reset();
        $this->terminal = new Terminal();
        $this->emitTo('admin.cash-closing.index', 'render');
        $this->emit('success', 'Cierre de caja realizado con éxito');
    }
}

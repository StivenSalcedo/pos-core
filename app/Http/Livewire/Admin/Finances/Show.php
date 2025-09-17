<?php

namespace App\Http\Livewire\Admin\Finances;

use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Log;
use App\Models\DetailFinance;
use App\Models\Finance;
use App\Models\PaymentMethod;
use App\Traits\LivewireTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{

    use LivewireTrait;

    protected $listeners = ['openShow'];

    public $openShow, $finance, $paymentMethods;

    public $value, $payment_method_id = '';

    public function mount()
    {
        $this->paymentMethods = PaymentMethod::where('status', PaymentMethod::ACTIVE)->get()->pluck('name', 'id');
    }

    public function render()
    {
        return view('livewire.admin.finances.show');
    }

    public function updatedValue($value)
    {
        $this->resetValidation();
        if (is_numeric($value)) {
            if (($this->finance->paid + $value) > $this->finance->bill->total) {
                return $this->addError('value', 'El valor ingresado es mayor al saldo pendiente');
            }
        }
    }

    public function getPendingProperty()
    {
        if (is_numeric($this->value)) {
            return formatToCop($this->finance->pending - $this->value);
        } else {
            return formatToCop($this->finance->pending);
        }
    }

    public function openShow(Finance $finance)
    {
        $this->resetValidation();
        $this->finance = $finance;
        $this->openShow = true;
    }

    public function store()
    {
        $rules = [
            'payment_method_id' => 'required|exists:payment_methods,id',
            'value' => 'required|integer|min:1|max:999999999',
        ];

        $this->validate($rules, null, ['value' => 'agregar valor']);

        $this->validateTerminal();

        $this->finance->refresh();

        if (($this->finance->paid + $this->value) > $this->finance->bill->total) {
            return $this->addError('value', 'El valor ingresado es mayor al saldo pendiente');
        }

        try {

            DB::beginTransaction();

            $payment = $this->finance->details()->create([
                'value' => $this->value,
                'payment_method_id' => $this->payment_method_id,
                'terminal_id' => getTerminal()->id,
            ]);

            $this->finance->refresh();

            if ($this->finance->pending === 0) {
                $this->finance->status = '0';
                $this->finance->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage(), $this->finance->toArray());
            return $this->emit('error', 'Ha ocurrido un error inesperado al registrar el pago');
        }

        $this->dispatchBrowserEvent('print-ticket', FinanceController::getFinance($this->finance, $payment));
        $this->reset(['value', 'payment_method_id']);
        $this->emit('success', 'pago agregado con éxito');
        $this->emitTo('admin.finances.index', 'render');
    }

    public function deletePayment(DetailFinance $payment)
    {
        $payment->delete();
        $this->finance->status = '1';
        $this->finance->save();
        $this->finance->refresh();
        $this->emitTo('admin.finances.index', 'render');
        return $this->emit('success', 'Pago eliminado con éxito');
    }

    public function showTicket(DetailFinance $detail)
    {
        $this->dispatchBrowserEvent('print-ticket', FinanceController::getFinance($this->finance, $detail));
    }
}

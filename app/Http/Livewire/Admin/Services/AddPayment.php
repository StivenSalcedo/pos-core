<?php

namespace App\Http\Livewire\Admin\Services;

use App\Models\ServicePayment;
use App\Models\PaymentMethod;
use Livewire\Component;
use WireUi\Traits\Actions;

class AddPayment extends Component
{

    public $open = false;

    public $service_id;
    public $amount;
    public $payment_method_id;
    public $reference;

    public $paymentMethods = [];

    protected $listeners = ['openAddPayment'];

    protected $messages = [
        'amount.required' => 'Debe ingresar un monto.',
        'amount.numeric'  => 'El monto debe ser numérico.',
        'payment_method_id.required' => 'Debe seleccionar un método de pago.',
    ];

    protected function rules()
    {
        return [
            'amount' => 'required|numeric|min:100',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'reference' => 'nullable|string|max:100',
        ];
    }

    public function mount()
    {
        $this->paymentMethods = PaymentMethod::where('status', '0')
            ->pluck('name', 'id');
    }

    public function openAddPayment($service_id)
    {
        $this->resetValidation();
        $this->reset(['amount', 'payment_method_id', 'reference']);
        $this->service_id = $service_id;
        $this->open = true;
    }

    public function save()
    {
        $this->validate();

        ServicePayment::create([
            'service_id'        => $this->service_id,
            'amount'            => $this->amount,
            'payment_method_id' => $this->payment_method_id,
            'reference'         => $this->reference,
            'user_id'           => auth()->id(),
        ]);

              $this->emit('success', 'El pago fue agregado correctamente.');
       

        $this->emitTo('admin.services.edit', 'refreshPaymentDetails');
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.admin.services.add-payment');
    }
}

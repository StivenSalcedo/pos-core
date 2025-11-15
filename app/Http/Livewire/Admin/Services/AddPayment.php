<?php

namespace App\Http\Livewire\Admin\Services;

use App\Models\ServicePayment;
use App\Models\PaymentMethod;
use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\Service;

class AddPayment extends Component
{

    public $open = false;

    public $service_id;
    public $amount;
    public $payment_method_id;
    public $reference;
    public $balance = 0;
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
            'amount' => 'required|numeric|min:50|max:' . $this->balance,
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
        $this->balance = $this->calculateBalance();
    }

    public function calculateBalance()
    {
        // Obtener el servicio
        $service = Service::with('products', 'payments')->find($this->service_id);
        /** ------------------------------------------
         * 1) Calcular subtotal de productos
         * ------------------------------------------ */
        $subtotal = $service->products->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        /** ------------------------------------------
         * 2) Calcular descuento total
         *    (si el descuento ya viene aplicado como
         *     valor fijo por producto, ajústalo aquí)
         * ------------------------------------------ */
        $discount = $service->products->sum(function ($item) {
            return $item->discount ?? 0;
        });

        /** ------------------------------------------
         * 3) Total del servicio
         * ------------------------------------------ */
        $totalServicio = $subtotal - $discount;

        /** ------------------------------------------
         * 4) Pagos ya realizados
         * ------------------------------------------ */
        $pagado = $service->payments->sum('amount');

        return ($totalServicio - $pagado);
        /** ------------------------------------------
         * 5) Validar que el nuevo pago no exceda
         * ------------------------------------------ */
    }

    public function save()
    {
        $this->validate();
        $balance = $this->calculateBalance();
        if (($balance - $this->amount) < 0) {
            return $this->emit('error', "El pago excede el saldo pendiente. debe: " . number_format($balance, 0));
        }

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

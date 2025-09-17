<?php

namespace App\Http\Livewire\Admin\Bills;

use App\Exceptions\CustomException;
use App\Http\Controllers\Log;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Rules\Date;
use App\Services\BillService;
use App\Services\DetailBillService;
use App\Services\DocumentTaxService;
use App\Services\FactusConfigurationService;
use App\Services\TerminalService;
use App\Traits\UtilityTrait;
use App\Utilities\CalcItemValues;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Create extends Component
{
    use UtilityTrait;

    public $paymentMethods;

    public $openChange = false;

    public $cash;

    public $products;

    public $customer = [];

    public $finance = 0;

    public $customerDefault;

    public $payment_method_id = '';

    public $due_date;

    public $observation;

    public $electronicInvoice = 0;

    protected $validationAttributes = [
        'customer.id' => 'cliente',
        'products' => 'productos',
        'finance' => 'método de pago',
        'payment_method_id' => 'medio de pago',
    ];

    protected $messages = [
        'products.required' => 'Seleccione uno o más productos',
    ];

    protected function rules()
    {
        return [
            'customer.id' => 'required|integer|exists:customers,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.discount' => 'nullable|integer|min:0|max:999999999',
            'finance' => 'required|integer|min:0|max:1',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'due_date' => ['nullable', 'date', new Date],
            'observation' => 'nullable|string|max:1000',
        ];
    }

    public function mount()
    {
         
        $paymentMethods = PaymentMethod::where('status', PaymentMethod::ACTIVE)->get();
        $this->paymentMethods = $paymentMethods->pluck('name', 'id');
        $this->payment_method_id = $paymentMethods->where('default', '1')->count() ? $paymentMethods->where('default', '1')->first()->id : '';
        $this->products = collect();
        $this->customerDefault = Customer::find(1)->toArray();

    }

    public function render()
    {
        return view('livewire.admin.bills.create')->layoutData(['title' => 'Crear factura']);
         
    }
 
   
    public function openChange()
    {
        // $this->dispatchBrowserEvent('print-ticket', 100);
        // return;

        $this->due_date = $this->finance == 1 ? $this->due_date : null;
        $this->due_date = empty($this->due_date) ? null : $this->due_date;

        $this->resetValidation();
        $this->validate();

        $total = collect($this->products)->sum('total');

        if (intval($this->payment_method_id) === PaymentMethod::CASH && intval($this->finance) === 0) {
            return $this->dispatchBrowserEvent('open-change', $total);
        }

        $this->cash = $total;
        $this->store();
    }

    public function store()
    {


       $products = collect($this->products);
        $productsDB = BillService::getUniqueProductsDB(clone $products);
        BillService::addCostToItems($products, $productsDB);

        try {
            TerminalService::verifyTerminal();
            BillService::validateInventory($products, $productsDB);
            BillService::calcTotales($products, $productsDB);
            $products = CalcItemValues::taxes($products);
        } catch (CustomException $ce) {
            return $this->emit('error', $ce->getMessage());
        }

        $lock = Cache::lock('createBill', 30);

        try {
            $lock->block(30);

            DB::beginTransaction();

            $bill = BillService::store($products, $this->payment_method_id, $this->cash, $this->customer['id'], 0, $this->observation);

            foreach ($products as $product) {
                $detailBill = DetailBillService::store($bill, $product);
                BillService::updateUnitsOrStock($product, $productsDB);
                DocumentTaxService::calcTaxRatesForItems($detailBill, $product['tax_rates']);
            }

            DocumentTaxService::calcTaxRatesForDocument($bill);

            BillService::updateStock($productsDB);

            if ($this->finance === '1') {
                $bill->finance()->create(['due_date' => $this->due_date]);
            }

            DB::commit();
        } catch (LockTimeoutException $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return $this->emit('error', 'Ha ocurrido un error inesperado. Vuelve a intentarlo');
        } catch (CustomException $ce) {
            DB::rollBack();

            return $this->emit('error', $ce->getMessage());
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage(), $products->toArray(), $th->getLine());

            return $this->emit('error', 'Ha sucedido un error inesperado. Vuelve a intentarlo');
        } finally {
            optional($lock)->release();
        }

         if (FactusConfigurationService::isApiEnabled() && (intval($this->electronicInvoice) === 0)) {
            if ($this->validateElectronicBill($bill)) {
                return;
            }
        }

        $this->products = collect([]);
        $this->customerDefault = Customer::find(1);
       $this->resetExcept('paymentMethods');
        $this->dispatchBrowserEvent('reset-properties-bill');
        $this->emitTo('admin.products.search', 'getProducts');

        $this->dispatchBrowserEvent('print-ticket', $bill->id);
    }

    public function validateElectronicBill(Bill $bill)
    {
        try {
            BillService::validateElectronicBill($bill);
        } catch (CustomException $ce) {
            Log::error($ce->getMessage());

            return $this->emit('error', $ce->getMessage());
        } catch (ValidationException $ce) {
            $errors = $ce->errors();
            foreach ($errors as $field => $errorMessages) {
                foreach ($errorMessages as $errorMessage) {
                    $this->addError($field, $errorMessage);
                }
            }

            return 'error';
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [], $th->getLine());

            return $this->emit('error', 'Ha sucedido un error inesperado. Vuelve a intentarlo');
        }
    }
}

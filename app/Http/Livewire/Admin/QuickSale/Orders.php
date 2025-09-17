<?php

namespace App\Http\Livewire\Admin\QuickSale;

use App\Exceptions\CustomException;
use App\Http\Controllers\Log;
use App\Models\Bill;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use App\Services\BillService;
use App\Services\DetailBillService;
use App\Services\DocumentTaxService;
use App\Services\FactusConfigurationService;
use App\Services\TerminalService;
use App\Traits\OrderTrait;
use App\Traits\UtilityTrait;
use App\Utilities\CalcItemValues;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Orders extends Component
{
    use OrderTrait;
    use UtilityTrait;

    public function mount() {}

    public function render()
    {
        return view('livewire.admin.quick-sale.orders');
    }

    public function getOrders()
    {
        return Order::select('id', 'name', 'customer', 'products', 'total','delivery_address')->get()->toArray();
    }

    public function store($order)
    {

        if (! collect($order['products'])->count()) {
            return $this->emit('alert', 'Agrega uno o mas productos');
        }

        if (! Order::find($order['id'])->is_available) {
            return $this->emit('alert', 'Esta mesa se encuentra ocupada. Selecciona otra mesa');
        }

        $this->updateOrder($order);

        $this->emit('success', 'Orden guardada con éxito');

        return 'success';
    }

    public function update($order)
    {
        if (! collect($order['products'])->count()) {
            return $this->emit('alert', 'Agrega uno o mas productos');
        }

        if (Order::find($order['id'])->is_available) {
            return $this->emit('alert', 'Esta mesa no se encuentra ocupada. Actualiza la vantana del navegador');
        }

        $this->updateOrder($order);

        $this->emit('success', 'Orden actualizada con éxito');

        return 'success';
    }

    public function updateCustomer(array $orderArray)
    {
        $order = Order::find($orderArray['id']);

        if ($order->is_available) {
            $this->emit('alert', $order->name.' no contiene una orden');

            return;
        }

        if (! count($orderArray['customer']) || ! is_array($orderArray['customer'])) {
            $this->emit('alert', 'Selecciona un cliente');

            return;
        }

        if (! Customer::where('id', $orderArray['customer']['id'])->exists()) {
            $this->emit('alert', "El cliente {$orderArray['customer']['names']} no se encuentra registrado");

            return;
        }

        $order->customer = $orderArray['customer'];
        $order->save();

        $this->emit('success', 'Cliente actualizado con éxito');

        return 'success';
    }

    public function updateTable(array $fromOrder, array $toOrder)
    {
        $fromOrder = Order::find($fromOrder['id']);
        $toOrder = Order::find($toOrder['id']);

        if (! $toOrder->is_available) {
            $this->emit('alert', "La {$toOrder['name']} está ocupada");

            return;
        }

        if ($fromOrder->is_available) {
            $this->emit('alert', 'Ha ocurrido un error inesperado. Vuelve a intentarlo');

            return;
        }

        try {
            DB::beginTransaction();

            $toOrder->customer = $fromOrder->customer;
            $toOrder->products = $fromOrder->products;
            $toOrder->total = $fromOrder->total;
            $toOrder->save();

            $this->destroy($fromOrder->id);

            DB::commit();
        } catch (\Throwable $th) {
            $this->emit('alert', 'Ha ocurrido un error inesperado. Vuelve al intentarlo');
            DB::rollBack();

            return;
        }

        $this->emit('success', 'Orden cambiada de mesa con éxito');

        return 'success';
    }

    public function destroy($order_id)
    {
        $order = Order::find($order_id);

        $orderDefault = [
            'name' => 'Mesa '.$order->id,
            'customer' => [],
            'products' => [],
            'total' => 0,
            'delivery_address' => ''
        ];

        $order->fill($orderDefault);
        $order->save();

        $this->emit('success', 'Orden eliminada con éxito');

        return 'success';
    }

    public function storeBill(array $order, $cash, $tip, $paymentMethod)
    {
        if (! $order['is_available']) {
            if (Order::find($order['id'])->is_available) {
                return;
            }
        }

        $products = collect($order['products']);
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

            $bill = BillService::store($products, $paymentMethod, $cash, $order['customer']['id'], $tip, '');

            foreach ($products as $product) {
                $detailBill = DetailBillService::store($bill, $product);
                BillService::updateUnitsOrStock($product, $productsDB);
                DocumentTaxService::calcTaxRatesForItems($detailBill, $product['tax_rates']);
            }

            DocumentTaxService::calcTaxRatesForDocument($bill);

            BillService::updateStock($productsDB);

            if (array_key_exists('id', $order)) {
                $this->destroy($order['id']);
            }

            DB::commit();
        } catch (LockTimeoutException $e) {
            Log::error($e);
            DB::rollBack();

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

        if (FactusConfigurationService::isApiEnabled()) {
            if ($this->validateElectronicBill($bill)) {
                return;
            }
        }

        $this->emit('success', 'Factura registrada con éxito');
        $this->dispatchBrowserEvent('quick-sale-print-ticket', $bill->id);

        return 'success';
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

    public function createBillOnlyPrint(array $order, $isCommand = false)
    {
        $products = collect($order['products']);

        $tip = $this->calculateTip($products->sum('total'));

        $data = [
            'customer' => [
                'identification' => $order['customer']['no_identification'],
                'names' => $order['customer']['names'],
            ],
            'bill' => [
                'place'=> $order['name'],
                'subtotal' => $products->sum('total') + $tip,
                'tip' => $tip,
                'total' => $products->sum('total') + $tip,
                'user_name' => auth()->user()->name,
                'format_created_at' => Carbon::parse(now())->format('d-m-Y g:i A'),
            ],
            'products' => $products,
            'delivery_address' => $order['delivery_address'] ?? '',
        ];
        if (!$isCommand) {
            $this->dispatchBrowserEvent('print-pre-ticket', $data);
        }else{
            $this->dispatchBrowserEvent('print-command-bill', $data);
        }

        return 'success';
    }

    public function calculateTip($subtotal)
    {
        $company = Company::find(1);
        $percentage_tip = $company->percentage_tip;

        return (int) $subtotal * $percentage_tip / 100;
    }
}

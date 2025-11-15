<?php

namespace App\Services;

use App\Exceptions\CustomException;

use App\Models\Service;
use App\Services\Factus\ElectronicServiceService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class ServiceService
{
    public static function store(Collection $products, $payment_method_id, $cash, $customer_id, $tip = 0, $observation): Service
    {
        $taxRate = $products->map(fn ($item) => ['tax_amount' => collect($item['tax_rates'])->sum('tax_amount')])->sum('tax_amount');

        $service = Service::create([
            'reference_code' => Str::uuid()->toString(),
            'payment_method_id' => $payment_method_id,
            'tip' => $tip,
            'cost' => $products->sum('cost'),
            'subtotal' => ($products->sum('total') - $taxRate) + $products->sum('discount'),
            'discount' => $products->sum('discount'),
            'total' => $products->sum('total'),
            'cash' => $cash,
            'observation' => $observation,
            'terminal_id' => getTerminal()->id,
            'customer_id' => $customer_id,
            'user_id' => auth()->user()->id,
        ]);

        return $service;
    }

    public static function validateInventory(Collection $products, Collection $productsDB): void
    {
        foreach ($productsDB as $productDB) {

            if (! intval($productDB->has_inventory)) {

                if ($productDB->has_presentations === '0') {

                    $result = $products->where('id', $productDB->id);

                    $presentations = $productDB->presentations;

                    $quantity = 0;

                    foreach ($result as $value) {

                        if ($presentations->contains($value['presentation']['id'])) {
                            $presentation = $presentations->find($value['presentation']['id']);
                            $quantity += $presentation->quantity * $value['amount'];
                        } else {
                            Log::error('No se encontro la presentacion', $value['presentation']);
                            throw new CustomException('Ha ocurrido un error inesperado al momento de registrar la factura');
                        }
                    }

                    if ($quantity > $productDB->units) {
                        Log::error('La cantidad excede el stock', $productDB->toArray());
                        throw new CustomException("La cantidad del producto {$productDB->name} supera el stock");
                    }
                } else {
                    $amount = $products->where('id', $productDB->id)->sum('amount');

                    if ($amount > $productDB->stock) {
                        throw new CustomException("No se pudo completar la compra. Solo quedan {$productDB->stock} de {$productDB['name']} en el stock");
                    }
                }
            }

            foreach ($products as $key => $value) {
                if (array_key_exists('discount', $value) && $value['discount'] > ($value['price'] * $value['amount'])) {
                    throw new CustomException("El valor del descuento supera al valor total del siguiente producto: {$value['name']}");
                }
            }
        }
    }

    public static function getUniqueProductsDB(Collection $collection): Collection
    {
        $collection->transform(fn ($item) => $item['id']);

        return Product::whereIn('id', $collection->unique())->get();
    }

    public static function addCostToItems(Collection &$products, Collection $productDB): void
    {
        foreach ($products as $key => $value) {
            $product = $productDB->find($value['id']);

            if (! $product) {
                Log::error('No se encontro el producto en la DB', $value);
                throw new CustomException("No se encontro el producto $value[name] en la base de datos");
            }

            if (! intval($product['has_presentations'])) {
                $presentation = $product->presentations->find($value['presentation']['id']);
                $units = $presentation->quantity * $value['amount'];
                $costForUnit = bcdiv($product->cost, $product->quantity, 2);
                $value['cost'] = bcdiv($costForUnit * $units, '1');
            } else {
                $value['cost'] = $product->cost * $value['amount'];
            }

            $products[$key] = $value;
        }
    }

    public static function updateStock(Collection $productsDB): void
    {
        foreach ($productsDB as $value) {

            if (intval($value->has_inventory)) {
                return;
            }

            $product = Product::find($value->id);
            if (! intval($product->has_presentations)) {
                $product->stock = bcdiv($product->units / $product->quantity, '1');
                $product->save();
            }
        }
    }

    public static function calcTotales(Collection &$products, Collection $productsDB): void
    {
        foreach ($products as $key => $value) {

            $product = $productsDB->find($value['id']);

            if (! $product) {
                Log::error('No se encontro el producto en la DB', $value);
                throw new CustomException('Ha ocurrido un error inesperado. Vuelve a intentarlo');
            }

            if (! array_key_exists('discount', $value)) {
                $value['discount'] = 0;
            }

            if ($product->has_presentations === '0') {
                $presentation = $product->presentations->find($value['presentation']['id']);
                $value['price'] = $presentation->price;
                $value['total'] = ($presentation->price * $value['amount']) - $value['discount'];
            } else {
                $value['price'] = $product->price;
                $value['total'] = ($product->price * $value['amount']) - $value['discount'];
            }

            $products[$key] = $value;
        }
    }

    public static function updateUnitsOrStock(array $product, Collection $productsDB): void
    {
        $result = $productsDB->find($product['id']);

        if (intval($result->has_inventory)) {
            return;
        }

        $units = 0;

        if (! intval($result->has_presentations)) {
            $presentation = $result->presentations->find($product['presentation']['id']);
            $units = $presentation->quantity * $product['amount'];
            Product::where('id', $product['id'])->decrement('units', $units);
        } else {
            Product::where('id', $product['id'])->decrement('stock', $product['amount']);
        }
    }

    public static function validateElectronicBill(Service $service): void
    {
        if (FactusConfigurationService::isApiEnabled()) {
             $service->loadMissing(['products', 'payments','payments.paymentMethod']);
              
            $response = ElectronicServiceService::validate($service);
            Log::error($response);//('Valor de variable:', ['data' => $response]);
            $response = ElectronicServiceService::saveElectronicBill($response->json(), $service);
        }
    }

    public static function storeElectronicCreditNote(Service $service): void
    {
        if (FactusConfigurationService::isApiEnabled() && $service->electronicBill && ! $service->electronicCreditNote) {
            ElectronicServiceService::storeCreditNote($service);
        }
    }

    public static function validateElectronicCreditNote(Service $service): void
    {
        $service->refresh();
        if (FactusConfigurationService::isApiEnabled() && $service->electronicCreditNote) {
            $response = ElectronicServiceService::validateCreditNote($service);
            $response = ElectronicServiceService::saveCreditNote($response->json(), $service);
        }
    }
}

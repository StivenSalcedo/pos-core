<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\BillTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use BillTrait;

    public function printOrder(Order $order)
    {
        $products = collect($order['products']);

        $productsDB = $this->getUniqueProductsDB(clone $products);

        if (!$this->validateInventory($products, $productsDB)) return;

        if (!$this->calcTotales($products, $productsDB)) return;

        $customer = [
            'identification' => $order->customer['no_identification'],
            'names' => $order->customer['names']
        ];

        $bill = [
            'user_name' => auth()->user()->name,
            'format_created_at' => now()->format('d-m-Y H:i'),
            'subtotal' => $products->sum('total') + $products->sum('discount') - $this->calcTaxByTribute($products, 'INC') - $this->calcTaxByTribute($products, 'IVA'),
            'inc' => $this->calcTaxByTribute($products, 'INC'),
            'iva' => $this->calcTaxByTribute($products, 'IVA'),
            'tip' => $products->sum('total') * session('config')->format_percentage_tip,
            'discount' => $products->sum('discount'),
            'total' => $products->sum('total'),
            'final_total' => $products->sum('total') + bcdiv($products->sum('total') * session('config')->format_percentage_tip, '1'),
            'cash' => 0,
            'change' => 0
        ];

        $range = [];

        return response()->json(['data' => compact('products', 'customer', 'bill', 'range')]);
    }
}

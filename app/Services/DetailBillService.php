<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\DetailBill;

class DetailBillService
{
    public static function store(Bill $bill, array $product): DetailBill
    {
        $product['product_id'] = $product['id'];

        if (count($product['presentation'])) {
            $product['name'] = $product['name'].' ['.$product['presentation']['name'].']';
        }

        $product = collect($product);

        $product = $product->only(['name','cost', 'amount', 'discount', 'price', 'total', 'presentation', 'product_id']);

        return $bill->details()->create($product->toArray());
    }

}

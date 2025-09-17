<?php

namespace App\Traits;

use App\Models\Order;

trait OrderTrait
{
    protected function updateOrder($arrayOrder)
    {
        $order = Order::find($arrayOrder['id']);

        $arrayOrder['total'] = collect($arrayOrder['products'])->sum('total');

        $order->fill($arrayOrder);
        $order->save();

        return $order;
    }
}

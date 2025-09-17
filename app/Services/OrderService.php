<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    public function getUsedTables()
    {
        return Order::where('name_order', '!=', '')->get(['id', 'name_order'])->pluck('name_order');
    }
}

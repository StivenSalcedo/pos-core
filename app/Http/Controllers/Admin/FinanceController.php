<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailFinance;
use App\Models\Finance;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Esta funcion devuelve la informacion del abono a la fininciación para la impresion en el frontend
     */
    static function getFinance(Finance $finance, DetailFinance $detail)
    {
        $customer = $finance->customer;
        $bill = $finance->bill;

        $data = [
            'customer' => [
                'identification' => $customer->no_identification,
                'names' => $customer->names,
            ],
            'payment' => [
                'number' => $detail->id,
                'total' => $detail->value,
                'format_created_at' => $detail->format_created_at,
                'payment_method' => $detail->paymentMethod->name,
            ],
            'products' => [
                [
                    'name' => 'Abono a la factura ' . $bill->number,
                    'amount' => '1',
                    'total' => $detail->value
                ]
            ],
        ];

        return $data;
    }
}

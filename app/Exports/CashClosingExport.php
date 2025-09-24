<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CashClosingExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    public $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Fecha y hora',
            'No. cierre de caja',
            'Sede',
            'Usuario',
            'Efectivo',
            'Tarjeta crédito',
            'Tarjeta débito',
            'Transferencia',
            'Total ventas',
            'Egresos',
            'Propinas',
            'Base Inicial',
            'Dinero esperado en caja',
            'Dinero real en caja',
            'Total cierre',
            'Observaciones'
        ];
    }

    public function map($cashClosing): array
    {
        return [
            [
                $cashClosing->formatCreatedAt,
                $cashClosing->id,
                $cashClosing->terminal->name,
                $cashClosing->user->name,
                strval($cashClosing->cash),
                strval($cashClosing->credit_card),
                strval($cashClosing->debit_card),
                strval($cashClosing->transfer),
                strval($cashClosing->total_sales),
                strval($cashClosing->outputs),
                strval($cashClosing->tip),
                strval($cashClosing->base),
                strval($cashClosing->cash_register),
                strval($cashClosing->price),
                strval($cashClosing->price - $cashClosing->cash_register),
                $cashClosing->observations,
            ],
        ];
    }
}

<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\NumberingRange;

class NumberingRangeService
{
    /**
     * Obtiene el siguiente numero de factura
     */
    public static function nextNumber(): NumberingRange
    {

        $terminal = auth()->user()->terminals;

        if ($terminal->count() == 0) {
            throw new CustomException('Este usuario no cuenta con una terminal asignada');
        }

        if ($terminal->count() >= 2) {
            throw new CustomException('Este usuario cuenta con más de dos sedes asignadas');
        }

        $range = $terminal->first()->numberingRange;

        if ($range->status !== '0') {
            throw new CustomException("El rango de numeración $range->prefix ($range->from - $range->to) se encuentra inactivo");
        }

        if (now()->gt($range->expire)) {
            throw new CustomException("El rango de numeración $range->prefix ($range->from - $range->to) se encuentra vencido");
        }

        return $range;

        return $range->prefix.' - '.$range->current;

    }

    public static function incrementNumber(NumberingRange $range): void
    {
        if ($range->current >= $range->to) {
            throw new CustomException('El rango de numeración ya está en el límite autorizado');
        }

        NumberingRange::where('id', $range->id)->increment('current');
    }
}

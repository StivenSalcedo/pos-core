<?php

namespace App\Services;

use App\Exceptions\CustomException;

class TerminalService
{
    public static function verifyTerminal(): void
    {
        if (auth()->user()->terminals()->active()->get()->count() === 0) {
            throw new CustomException('No se ha configurado una terminal para este usuario');
        }
    }
}

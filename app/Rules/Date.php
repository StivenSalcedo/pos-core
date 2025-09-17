<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class Date implements Rule {

    public function __construct() {

    }

    public function passes($attribute, $value) {
        $fecha = Carbon::parse($value);
        $hoy = Carbon::now();
        return $fecha->gt($hoy) ? true : false;

    }

    public function message(){
        return 'La fecha debe ser mayor a la actual';
    }
}

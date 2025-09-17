<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Phone implements Rule
{
    public function __construct() {

    }

    public function passes($attribute, $value) {
        return (strlen(trim($value)) >= 7 &&  strlen(trim($value)) <= 20);
    }

    public function message(){
        return 'El campo :attribute debe tener entre 7 a 20 digitos';
    }
}

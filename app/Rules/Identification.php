<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Identification implements Rule {

    public function passes($attribute, $value) {
        return (strlen(trim($value)) >= 5  &&  strlen(trim($value)) <= 20) ;
    }

    public function message(){
        return 'El campo :attribute debe estar entre 7 y 20 caracteres';
    }
}

<?php

namespace App\Enums;

enum TypesProviders: string {
    case NATURAL = '1';
    case EMPRESA = '2';

    public function getLabel(){
        return match($this){
            TypesProviders::NATURAL => 'Natural',
            TypesProviders::EMPRESA => 'Empresa',
        };
    }

    static function getCases(){
        $cases = TypesProviders::cases();
        $array = [];
        foreach ($cases as $key => $value) {
            $array[] = $value->value;
        }

        return $array;
    }

    static function getCasesLabel(){
        $cases = TypesProviders::cases();
        $array = [];
        foreach ($cases as $key => $value) {
            $array[$value->value] = $value->getLabel();
        }

        return $array;
    }
}

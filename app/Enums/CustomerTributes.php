<?php

namespace App\Enums;

enum CustomerTributes : string
{
    case NOT_RESPONSIBLE = '21';
    case RESPONSIBLE = '18';

    public function getLabel(){
        return match($this){
            CustomerTributes::NOT_RESPONSIBLE => 'No responsable de IVA',
            CustomerTributes::RESPONSIBLE => 'Responsable de IVA',
        };
    }

    static function getCases(){
        $cases = CustomerTributes::cases();
        $array = [];
        foreach ($cases as $key => $value) {
            $array[] = $value->value;
        }

        return $array;
    }

    static function getCasesLabel(){
        $cases = CustomerTributes::cases();
        $array = [];
        foreach ($cases as $key => $value) {
            $array[$value->value] = $value->getLabel();
        }

        return $array;
    }
}

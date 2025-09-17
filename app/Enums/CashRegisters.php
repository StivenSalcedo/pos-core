<?php

namespace App\Enums;

enum CashRegisters: string {

    case MAIN = '1';
    case GENERAL = '2';

    public function getLabel(){
        return match($this){
            CashRegisters::MAIN => 'Caja',
            CashRegisters::GENERAL => 'General',
        };
    }

    static function getCases(){
        $cases = CashRegisters::cases();
        $array = [];
        foreach ($cases as $key => $value) {
            $array[] = $value->value;
        }

        return $array;
    }

    static function getCasesLabel(){
        $cases = CashRegisters::cases();
        $array = [];
        foreach ($cases as $key => $value) {
            $array[$value->value] = $value->getLabel();
        }

        return $array;
    }
}

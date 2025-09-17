<?php

namespace App\Enums;

enum LegalOrganization : string
{
    case LEGAL_PERSON = '1';
    case NATURAL_PERSON = '2';

    public function getLabel(){
        return match($this){
            LegalOrganization::LEGAL_PERSON => 'Persona jurídica',
            LegalOrganization::NATURAL_PERSON => 'Persona natural',
        };
    }

    static function getCases(){
        $cases = LegalOrganization::cases();
        $array = [];
        foreach ($cases as $key => $value) {
            $array[] = $value->value;
        }

        return $array;
    }

    static function getCasesLabel(){
        $cases = LegalOrganization::cases();
        $array = [];
        foreach ($cases as $key => $value) {
            $array[$value->value] = $value->getLabel();
        }

        return $array;
    }
}

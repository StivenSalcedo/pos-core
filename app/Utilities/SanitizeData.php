<?php

namespace App\Utilities;

class SanitizeData
{
    public static function trim(array $values, array $except = []): array
    {
        foreach ($values as $key => $value) {
            $values[$key] = array_key_exists($key, $except) ? $value : trim($value);
        }

        return $values;
    }
}

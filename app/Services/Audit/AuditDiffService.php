<?php

namespace App\Services\Audit;

class AuditDiffService
{
    public static function changes($audit): array
    {
        $old = $audit->old_values ?? [];
        $new = $audit->new_values ?? [];

        $changes = [];

        foreach ($new as $field => $value) {
            if (!array_key_exists($field, $old)) {
                continue;
            }

            if ($old[$field] != $value) {
                $changes[$field] = [
                    'from' => $old[$field],
                    'to'   => $value,
                ];
            }
        }

        return $changes;
    }
}

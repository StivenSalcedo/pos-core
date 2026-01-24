<?php

namespace App\Traits;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(fn($model) => self::audit($model, 'created'));
        static::updated(fn($model) => self::audit($model, 'updated'));
        static::deleted(fn($model) => self::audit($model, 'deleted'));
    }

    protected static function audit($model, string $event)
    {
        $oldValues = null;
        $newValues = null;

        switch ($event) {
            case 'created':
                // En creación solo interesa el estado nuevo
                $newValues = $model->getAttributes();
                break;

            case 'updated':
                // En update interesa el diff
                $oldValues = $model->getOriginal();
                $newValues = $model->getAttributes();
                break;

            case 'deleted':
                // En delete SOLO interesa lo que existía
                $oldValues = $model->getOriginal();
                break;
        }

        Audit::create([
            'event' => $event,
            'auditable_id' => $model->id,
            'auditable_type' => get_class($model),

            'parent_id' => method_exists($model, 'getAuditParentId')
                ? $model->getAuditParentId()
                : null,

            'parent_type' => method_exists($model, 'getAuditParentType')
                ? $model->getAuditParentType()
                : null,

            'old_values' => $oldValues,
            'new_values' => $newValues,

            'user_id' => auth()->id(),
        ]);
    }
}

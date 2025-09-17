<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentificationDocument extends Model
{
    use HasFactory;

    const CEDULA = 3;

    const NIT = 6;

    const PASAPORTE = 7;

    const DOCUMENTO_IDENTIFICACIÓN_EXTRANJERO = 8;

    const NIT_OTRO_PAIS = 10;

    const ACTIVE = 1;

    const FOREING_DOCUMENTS = [self::PASAPORTE, self::DOCUMENTO_IDENTIFICACIÓN_EXTRANJERO, self::NIT_OTRO_PAIS];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    //scopes
    protected function scopeEnabled(Builder $query)
    {
        return $query->where('is_enabled', self::ACTIVE);
    }
}

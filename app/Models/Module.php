<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Module extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'is_functionality' => false,
    ];

    public function name(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Str::ucfirst($value)
        );
    }

    public function isEnabled(): Attribute
    {
        return new Attribute(
            get: fn ($value) => (bool) $value
        );
    }

    public function isFunctionality(): Attribute
    {
        return new Attribute(
            get: fn ($value) => (bool) $value
        );
    }

    // Scopes
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', 1);
    }

    protected function scopeFunctionality($query)
    {
        return $query->where('is_functionality', 1);
    }

    protected function scopeModule($query)
    {
        return $query->where('is_functionality', 0);
    }
}

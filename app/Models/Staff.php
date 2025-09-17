<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Staff extends Model {

    use HasFactory;

    protected $guarded=['id'];

    protected $attributes=[
        'status' => '0'
    ];

    public function names(): Attribute{
        return new Attribute(
            get: fn($value) => Str::title($value),
            set: fn($value) => Str::lower($value)
        );
    }

    public function email(): Attribute{
        return new Attribute(
            set: fn($value) => empty($value) ? null : Str::lower($value)
        );
    }
}

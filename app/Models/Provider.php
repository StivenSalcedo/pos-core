<?php

namespace App\Models;

use App\Enums\TypesProviders;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model {

    use HasFactory;

    protected $guarded = ['id'];

    protected $attributes=[
        'status' => '0'
    ];

    protected $casts = [
        'type' => TypesProviders::class
    ];


    public function type() : Attribute{
        return new Attribute(
            set: fn($value) => $value ? $value : null
        );
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NumberingRange extends Model
{

    use HasFactory;

    protected $guarded = ['id'];

    protected $attributes = [
        'status' => '1'
    ];

    protected $casts = [
        'expire' => 'date',
        'date_authorization' => 'date'
    ];

    const ACTIVE = '0';
    const DESACTIVATED = '0';

    public function prefix(): Attribute
    {
        return new Attribute(
            set: fn ($value) => Str::upper($value)
        );
    }

    #appends
    protected function formatDateAuthorization(): Attribute
    {
        return new Attribute(
            get: fn () => $this->date_authorization->format('d-m-Y')
        );
    }
}

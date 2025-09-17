<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    use HasFactory;

    protected $guarded = ['id'];

    #accessor and mutator
    protected function percentageTip(): Attribute
    {
        return new Attribute(
            get: fn ($value) => (int) $value
        );
    }

    #appends
    protected function formatPercentageTip(): Attribute
    {
        return new Attribute(
            get: fn () => $this->percentage_tip / 100
        );
    }
}

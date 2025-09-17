<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model {

    use HasFactory;

    protected $guarded=['id'];

    const ERROR='1';
    const CRITICAL='2';

    public function data() : Attribute{
        return new Attribute(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value)
        );
    }

    public function level() : Attribute{
        return new Attribute(
            get: fn($value) => $value == 1 ? 'Error' : 'Critico',
        );
    }
    
}

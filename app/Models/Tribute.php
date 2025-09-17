<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tribute extends Model {

    use HasFactory;

    protected $guarded=['id'];

    protected $attributes = [
        'status' => '0',
    ];

    protected function scopeEnabled($query)
    {
        return $query->where('status', '0');
    }

}

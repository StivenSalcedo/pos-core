<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySaleDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dailySales()
    {
        return $this->hasOne(DailySale::class);
    }

}

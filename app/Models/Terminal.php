<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const ACTIVE = '0';

    const INACTIVE = '1';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function numberingRange()
    {
        return $this->belongsTo(NumberingRange::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function outputs()
    {
        return $this->hasMany(Output::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTax extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function documentTaxable()
    {
        return $this->morphTo();
    }

    public function taxRates()
    {
        return $this->hasMany(DocumentTaxRate::class);
    }

}

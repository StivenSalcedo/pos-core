<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProduct extends Model
{
    use HasFactory;
     protected $guarded = ['id'];

      public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function documentTaxes()
    {
        return $this->morphMany(DocumentTax::class, 'document_taxeable');
    }
}

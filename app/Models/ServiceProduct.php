<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ServiceProduct extends Model
{
    use HasFactory;
    use Auditable;
     protected $guarded = ['id'];

      public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function documentTaxes()
    {
        return $this->morphMany(DocumentTax::class, 'document_taxeable');
    }

       public function getAuditParentId()
    {
        return $this->service_id;
    }

    public function getAuditParentType()
    {
        return Service::class;
    }
}

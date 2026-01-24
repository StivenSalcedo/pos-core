<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ServiceAttachment extends Model
{
    use HasFactory;
    use Auditable;
     protected $guarded = ['id'];

      public function service()
    {
        return $this->belongsTo(Service::class);
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

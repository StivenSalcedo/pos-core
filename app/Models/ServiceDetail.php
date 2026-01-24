<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ServiceDetail extends Model
{
    
    use HasFactory;
    use Auditable;

    protected $fillable = [
    'service_id',
    'component_id',
    'quantity',
    'reference',
    'capacity',
    'brand_id'
];

 // 🔹 Relación hacia el servicio
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // 🔹 Relación hacia el componente (importante)
    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    // 🔹 Relación hacia la marca (opcional)
    public function brand()
    {
        return $this->belongsTo(Brand::class);
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

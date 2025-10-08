<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    use HasFactory;
    protected $fillable = [
    'service_id',
    'component_id',
    'quantity',
    'reference',
    'capacity',
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

}

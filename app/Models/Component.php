<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;
    public function equipmentTypes() {
        return $this->belongsToMany(\App\Models\EquipmentType::class, 'equipment_type_component')
                ->withPivot('default_quantity')->withTimestamps();
    }
}

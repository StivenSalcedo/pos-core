<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;
    public function components() { 
    return $this->belongsToMany(\App\Models\Component::class, 'equipment_type_component')
                ->withPivot('default_quantity')->withTimestamps();
}
}

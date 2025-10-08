<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
public function customer() { return $this->belongsTo(\App\Models\Customer::class); }
public function equipmentType() { return $this->belongsTo(\App\Models\EquipmentType::class); }
public function brand() { return $this->belongsTo(\App\Models\Brand::class); }
public function state() { return $this->belongsTo(\App\Models\ServiceState::class); }
public function details() { return $this->hasMany(\App\Models\ServiceDetail::class); }
public function products() { return $this->hasMany(\App\Models\ServiceProduct::class); }
public function payments() { return $this->hasMany(\App\Models\ServicePayment::class); }
public function attachments() { return $this->hasMany(\App\Models\ServiceAttachment::class); }
public function notifications() { return $this->hasMany(\App\Models\ServiceNotification::class); }
public function responsible()
{
    return $this->belongsTo(User::class, 'responsible_id');
}

}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


     protected function isElectronic(): Attribute
    {
        return new Attribute(
            get: fn () => $this->electronicBill ? true : false
        );
    }

    protected function isValidated(): Attribute
    {
        return new Attribute(
            get: fn () => $this->electronicBill && $this->electronicBill->is_validated
        );
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }
    public function equipmentType()
    {
        return $this->belongsTo(\App\Models\EquipmentType::class);
    }
    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }
    public function state()
    {
        return $this->belongsTo(\App\Models\ServiceState::class);
    }
    public function details()
    {
        return $this->hasMany(\App\Models\ServiceDetail::class);
    }
    public function products()
    {
        return $this->hasMany(\App\Models\ServiceProduct::class);
    }
    public function payments()
    {
        return $this->hasMany(\App\Models\ServicePayment::class);
    }
    public function attachments()
    {
        return $this->hasMany(\App\Models\ServiceAttachment::class);
    }
    public function notifications()
    {
        return $this->hasMany(\App\Models\ServiceNotification::class)->orderBy('created_at', 'desc');
    }
    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
    public function techassigned()
    {
        return $this->belongsTo(User::class, 'tech_assigned_id');
    }

    public function electronicBill()
    {
        return $this->hasOne(ElectronicService::class);
    }

   

    protected $casts = [
        'date_entry' => 'datetime:Y-m-d',
    ];
}

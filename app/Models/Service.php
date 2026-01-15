<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    protected function isElectronic(): Attribute
    {
        return new Attribute(
            get: fn() => $this->electronicBill ? true : false
        );
    }

    protected function isValidated(): Attribute
    {
        return new Attribute(
            get: fn() => $this->electronicBill && $this->electronicBill->is_validated
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

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }



    protected $casts = [
        'date_entry' => 'datetime:Y-m-d',
    ];

    public function scopeFilterByTerminalPermission(
        Builder $query,
        $user,
        $terminalId = null
    ) {
        // ❌ Usuario SIN permiso → solo su sede actual
        if (! $user->can('ver todas las sedes')) {
            $currentTerminalId = optional($user->terminals->first())->id;

            if ($currentTerminalId) {
                $query->where('terminal_id', $currentTerminalId);
            }

            return $query;
        }

        // ✅ Usuario CON permiso
        // 👉 si selecciona una sede, filtra
        // 👉 si selecciona "todas", NO filtra
        if (! empty($terminalId)) {
            $query->where('terminal_id', $terminalId);
        }

        return $query;
    }
}

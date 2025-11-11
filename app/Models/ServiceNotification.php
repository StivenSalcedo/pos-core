<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'channel', // 'email', 'sms', 'whatsapp'
        'destination',
        'sent_at',
        'message',
        'status',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mockery\Generator\Method;
use App\Traits\Auditable;

class ServicePayment extends Model
{
    use HasFactory;
    use Auditable;
    protected $guarded = ['id'];
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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

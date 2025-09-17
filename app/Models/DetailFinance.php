<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailFinance extends Model
{

    use HasFactory;

    protected $guarded = ['id'];

    #appends
    protected function formatCreatedAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->created_at->format('d-m-Y h:i')
        );
    }

    #relations
    public function finance()
    {
        return $this->belongsTo(Finance::class);
    }

    public function bill()
    {
        return $this->hasOneThrough(Bill::class, Finance::class, 'id', 'id', 'finance_id', 'bill_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}

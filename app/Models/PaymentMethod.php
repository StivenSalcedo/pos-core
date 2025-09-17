<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model {

    use HasFactory;

    const ACTIVE='0';
    const INACTIVE='1';

    const CASH=1;
    const CREDIT_CARD=2;
    const DEBIT_CARD=3;
    const TRANSFER=4;

    protected $guarded=['id'];
    
}

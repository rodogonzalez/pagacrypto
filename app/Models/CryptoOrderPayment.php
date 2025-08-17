<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class CryptoOrderPayment extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'crypto_order_payments';

    protected $fillable = [
        'order_id',
        'payment_details',        
        'amount_received',
        'fee',
        'transaction_id'
    ];
    
}
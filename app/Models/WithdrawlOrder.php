<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawlOrder extends Model
{
    //

    protected $table = 'store_withdrawal_orders';

    protected $fillable = [
        'store_withdrawal_id',
        'orders_id',


    ];
    protected $guarded = ['id'];
}

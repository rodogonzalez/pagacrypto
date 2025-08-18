<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Scopes\CurrentUserOwnerScope;

class Order extends Model
{
    use CrudTrait;
    protected $fillable = [
        'customer_id',
        'users_id',
        'stores_id',
        'fiat_total_amount',
        'status',
        'crypto_wallet_total_amount',
        'currency',
        'completed_at'
    ];



    public function payments()
    {
        //return $this->hasMany(CryptoOrderPayment::class);

        $items = DB::table('crypto_order_payments')
            ->where('order_id', $this->id)
            ->get();

        return $items;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getCustomerName()
    {
        if($this->customer) {
            return $this->customer->first_name . ' ' . $this->customer->last_name;
        }
        return __('customer.working');
    }



    public function crypto_payments()
    {
        return $this->hasMany(CryptoOrderPayment::class);
    }


    public function crypto_paid_total()
    {
        //if ($this->crypto_payments()->count()==0) return 0;

        return $this->crypto_payments->map(function ($i){
            return ($i->amount_received + $i->fee);
        })->sum();
    }


    public function receivedAmount()
    {
        return $this->payments->map(function ($i){
            return $i->amount;
        })->sum();
    }

    public function formattedReceivedAmount()
    {
        return number_format($this->receivedAmount(), 2);
    }

    public function show_me()
    {
        return '<a  href="/admin/order/view/' . ($this->id) . '" class="btn btn-sm btn-link"> Ver</a>';
    }



    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CurrentUserOwnerScope);
    }



}

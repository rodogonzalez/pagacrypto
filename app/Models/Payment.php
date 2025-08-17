<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CurrentUserOwnerScope;

class Payment extends Model
{
    use CrudTrait;
    protected $fillable = [
        'amount',
        'order_id',
        'users_id',
    ];


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CurrentUserOwnerScope);
    }


}

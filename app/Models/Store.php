<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CurrentUserOwnerScope;

class Store extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $table = 'stores';

    protected $fillable = [
        'users_id',
        'name',
        'position_lng',
        'position_lat',
    ];


    public function products(){

        return $this->hasMany('App\Models\Product', 'local_stores');

    }



    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CurrentUserOwnerScope);
    }



}

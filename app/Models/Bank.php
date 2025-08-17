<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CurrentUserOwnerScope;


class Bank extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'banks';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded  = ['id'];
    protected $fillable = [
        'users_id',
        'name',
        'owner_name',
        'owner_id',
        'owner_id_picture_front',
        'owner_id_picture_back',
        'owner_phone',
        'iban_account',
        'status',
    ];




    // protected $hidden = [];


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CurrentUserOwnerScope);
    }

}

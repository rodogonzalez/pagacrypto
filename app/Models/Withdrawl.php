<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CurrentUserOwnerScope;
class Withdrawl extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */


    protected $table = 'withdrawals';

    protected $fillable = [
        'users_id',
        'processed_by_users_id',
        'amount',
        'iban_account',
        'bank_name',
        'account_owner_name',
        'status',
        'bank_transaction_id',
        'notes'
    ];
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CurrentUserOwnerScope);
    }

    public static function getPending(){
        return self::where('status','requested')->get();

    }



}

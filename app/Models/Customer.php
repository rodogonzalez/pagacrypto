<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Scopes\CurrentUserOwnerScope;
class Customer extends Model
{
    use CrudTrait;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'avatar',
        'users_id',
    ];

    public function getAvatarUrl()
    {
        return Storage::url($this->avatar);
    }


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CurrentUserOwnerScope);
    }


}

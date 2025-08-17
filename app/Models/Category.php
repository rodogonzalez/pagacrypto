<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\CurrentUserOwnerScope;

class Category extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = [
        'users_id',
        'name',
    ];


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CurrentUserOwnerScope);
    }



}

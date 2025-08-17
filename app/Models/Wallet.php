<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'stores_id',
        'currency',
        'api_key',
        'password',
        'label'
    ];    

}

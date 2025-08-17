<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'stores_id',
        'product_categories_id',
        'name',
        'description',
        'image',
        'barcode',
        'price',
        'quantity',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function getImageUrl()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }

        return asset('images/img-placeholder.jpg');
    }

    public function getImageAttribute($value)
    {
        if (is_null($value)) {
            return 'no-image.png';
        }

        return $value;
    }

    public function getThumbAttribute($value)
    {
        if (is_null($this->image)) {
            return '/storage/products/no-image.png';
        }

        return '/storage/products/' . $this->image;
    }

    protected function getStorenameAttribute()
    {
        $record = \App\Models\Local::where('id', $this->stores_id)->first();
        if (!is_null($record)) {
            return $record->name;
        }

        return '';
        // return $this->Store->first()->name;
    }

    protected function getCategorynameAttribute()
    {
        $record = \App\Models\Category::where('id', $this->product_categories_id)->first();
        if (!is_null($record)) {
            return $record->name;
        }

        return 'xxx';
        // return $this->Store->first()->name;
    }
}

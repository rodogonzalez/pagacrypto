<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CurrentUserOwnerScope;
use App\Scopes\StoreDetailsScope;


class Local extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'stores';

    protected $fillable = [
        'users_id',
        'name',
        'phone',
        'website',
        'email',
        'parking_limit',
        'description',
        'position_lng',
        'position_lat',
        'logo',
        'store_categories_id',
        'wallet_ltc',
        'wallet_bch',
        'wallet_doge'
    ];

    protected $casts = [
        // 'store_categories' => 'array',
    ];

    public function show_products()
    {
        return '<a  target="_blank" href="/admin/product/?store_id=' . urlencode($this->id) . '" class="btn btn-sm btn-link"> Products</a>';
    }


    public function show_orders()
    {
        return '<a  target="_blank" href="/admin/order/?stores_id=' . urlencode($this->id) . '" class="btn btn-sm btn-link"> Ordenes</a>';
    }


    public function show_pos()
    {
        return '<a  target="_blank" href="/admin/pos/' . urlencode($this->id) . '" class="btn btn-sm btn-link"> Punto de Venta</a>';
    }

    public function getThumbAttribute($value)
    {
        if (is_null($this->logo)) {
            return '/storage/stores/no-image.png';
        }

        return '/storage/stores/' . $this->logo;
    }

    public function getCategoryAttribute($value)
    {

        if (is_null($this->store_categories_id)) {
            return 'Sin categorizar';
        }
        return \App\Models\LocalType::where('id',$this->store_categories_id)->first()->name;
        //return '/storage/stores/' . $this->logo;
    }


    public function get_total_orders($date_start = null, $date_end = null){


    }


    public function products(){
        //return \App\Models\Product::where('stores_id',$this->id)->get();
        return $this->hasMany('App\Models\Product', 'stores_id');
    }


    public static function stores_summary(){
        static::addGlobalScope(new StoreDetailsScope);
        $stores  = SELF::all();

        $response = array();

        foreach ($stores as $store){


            $store_data = $store->toArray();
            $response[] = $store_data;


        }


        return json_encode($response);
    }


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CurrentUserOwnerScope);
        //static::addGlobalScope(new StoreDetailsScope);
    }


}

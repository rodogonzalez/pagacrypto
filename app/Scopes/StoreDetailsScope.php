<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class StoreDetailsScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check()) {

            $builder->select([\DB::raw('stores.*'),\DB::raw('store_categories.icon') ,\DB::raw('store_categories.name as category_name'),\DB::raw('count(DISTINCT orders.id) as total_orders'),\DB::raw('count(DISTINCT products.id) as total_products')])
            ->leftJoin('products', 'products.stores_id', '=', 'stores.id')
            ->leftJoin('orders', 'orders.stores_id', '=', 'stores.id')
            ->leftJoin('store_categories', 'store_categories.id', '=', 'stores.store_categories_id')
            ->groupBy('stores.id')
            ;

/*

            $products = \App\Models\Product::where('stores_id',$store->id)->get()->count();
            $orders = \App\Models\Order::where('stores_id',$store->id)->where('status','completed')->get()->count();
*/
        }
    }
}

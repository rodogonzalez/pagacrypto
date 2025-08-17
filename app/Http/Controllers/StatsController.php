<?php

namespace App\Http\Controllers;

use App\Models\Local;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function generate_store_category_stats()
    {
        $stores             = \App\Models\Local::all();
        $store_filters      = [];
        $store_query_filter = '';
        if ($stores->count() != 0) {
            foreach ($stores as $store) {
                $store_filters[] = 'products.stores_id = ' . $store->id;
            }

            $products = DB::table('product_categories')
                ->select([DB::raw('count(*) as total'), 'product_categories.name as label'])
                ->join('products', 'product_categories.id', '=', 'products.product_categories_id')
                ->whereRaw(implode(' or ', $store_filters))
                ->groupBy('product_categories.name')
                ->get();
        } else {
            $products = DB::table('product_categories')
                ->select(DB::raw('0 as total, name as label'))
                ->get();
        }

        $response = ['totals' => [], 'labels' => []];
        foreach ($products as $product_info) {
            $response['totals'][] = $product_info->total;
            $response['labels'][] = $product_info->label;
        }

        return json_encode($response);
    }

    public function get_stores_summary()
    {

        $current_user = backpack_user();
        if (is_null($current_user)) {
            return;
        }

        $stores_data = DB::table('orders')
            ->select([DB::raw('count(*) as total'), 'stores.name as label'])
            ->join('stores', 'stores.id', '=', 'orders.stores_id')
            ->where('stores.users_id', $current_user->id)
            ->groupBy('stores.name')
            ->get();

        $response = ['totals' => [], 'labels' => []];
        foreach ($stores_data as $store_info) {
            $response['totals'][] = $store_info->total;
            $response['labels'][] = $store_info->label;
        }

        return json_encode($response);
    }

    public function get_today_sales()
    {

        $total_sold = Order::whereRaw(' DATE(completed_at) = CURDATE() ')->get()->count();

        return $total_sold;
    }

    public function get_total_sales()
    {

        $current_user = backpack_user();
        if (is_null($current_user)) {
            return;
        }

        $data          = DB::table('orders')
            ->select([DB::raw(' ROUND( sum(order_items.price),2) as amount')])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.users_id', $current_user->id)
            ->where('orders.status', 'completed')
            ->get();
        $total_result  = array();
        $labels_result = array();

        $total_orders = Order::where('status', 'completed')->get()->count();

        foreach ($data as $row) {
            $total_result[] = $total_orders;
            //$labels_result[] = $row->amount;
            break;
        }

        $result = [
            'totals' => $total_orders,
            'amount' => $row->amount
        ];

        return $result;

        //$total_sold  = Order::whereRaw( ' DATE(completed_at) >  "' . date( 'Y-m-d', strtotime( '-4 week -7 days' ) ) . '"' )->select( DB::raw( 'count(*) as number, sum()' ) )->get();

        //return $total_sold;
    }

    public function get_new_total_products()
    {

        $total_sold = Product::whereRaw(' DATE(created_at) >  "' . date('Y-m-d', strtotime('-7 days')) . '"')->get()->count();

        return $total_sold;
    }

    public function get_sales_period()
    {

        $current_user = backpack_user();
        if (is_null($current_user)) {
            return;
        }

        $date = date('Y-m-d', strtotime('-15 days'));

        $data          = DB::table('orders')
            ->select([DB::raw('count(*) as total'), DB::raw('DATE(orders.completed_at) as label')])
            ->join('stores', 'stores.id', '=', 'orders.stores_id')
            ->where('stores.users_id', $current_user->id)
            ->whereRaw(DB::raw("DATE(completed_at) > '$date'"))
            ->groupBy(DB::raw('DATE(completed_at)'))
            ->orderBy(DB::raw('DATE(completed_at)'))
            ->get();
        $total_result  = array();
        $labels_result = array();
        foreach ($data as $row) {
            $total_result[]  = $row->total;
            $labels_result[] = $row->label;
        }

        $result = [
            'totals' => $total_result,
            'labels' => $labels_result
        ];

        return json_encode($result);
    }

    public function most_categories_sold()
    {

        $current_user = backpack_user();

        $result = DB::table(DB::raw('product_categories'))
            ->select([DB::raw('count( product_categories.id) as total'), DB::raw('product_categories.name')])
            ->join('products', 'products.product_categories_id', '=', 'product_categories.id')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.users_id', $current_user->id)
            ->where('orders.status', 'completed')
            ->groupBy(DB::raw('product_categories.id'))
            ->orderBy(DB::raw('total'), 'desc')
            ->get()->take(5);

        return json_encode($result);
    }

    public function get_most_sold_items()
    {

        $current_user = backpack_user();

        $result = DB::table(DB::raw('products'))
            ->select([DB::raw('products.id'), DB::raw('stores.name as store_name'), DB::raw('round(sum(order_items.quantity),2) as total'), DB::raw('products.name'), 'products.price', 'products.image', DB::raw('products.quantity as in_stock  ')])
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('stores', 'stores.id', '=', 'products.stores_id')
            ->where('orders.users_id', $current_user->id)
            ->where('orders.status', 'completed')
            ->groupBy(DB::raw('products.id'))
            ->orderBy(DB::raw('total'), 'desc')
            ->get()->take(8);
        return json_encode($result);
    }

    public function get_less_sold_items()
    {

        $current_user = backpack_user();

        $result = DB::table(DB::raw('products'))
            ->select([DB::raw('ROUND (sum(order_items.quantity),2) as total'), DB::raw('products.name'), 'products.price', 'products.image'])
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.users_id', $current_user->id)
            ->where('orders.status', 'completed')
            ->groupBy(DB::raw('products.id'))
            ->orderBy(DB::raw('total'), 'desc')
            ->get()->take(5);

        return json_encode($result);
    }

    //

    public function generate_summary_last_month_json()
    {

        //dd( $this->get_sales_period() );
        $dates_array = array();

        /*
         * Recuerdese filtrar por tienda/admin/ o tiendas
         */

        $dates_array['w5_start'] = date('Y-m-d', strtotime('-4 week -7 days'));
        $dates_array['w5_end']   = date('Y-m-d', strtotime('-4 week -1 day'));

        $total_sold5 = Order::whereRaw('(completed_at between DATE("' . $dates_array['w5_start'] . '") and DATE("' . $dates_array['w5_end'] . '"))')->get()->count();
        $label_sold5 = date('m-d', strtotime('-4 week -7 days')) . '/' . date('m-d', strtotime('-4 week -1 day'));

        $dates_array['w4_start'] = date('Y-m-d', strtotime('-3 week  -7 days'));
        $dates_array['w4_end']   = date('Y-m-d', strtotime('-3 week  -1 day'));
        $total_sold4               = Order::whereRaw('(completed_at between DATE("' . $dates_array['w4_start'] . '") and DATE("' . $dates_array['w4_end'] . '"))')->get()->count();

        $label_sold4 = date('m-d', strtotime('-3 week -7 days')) . '/' . date('m-d', strtotime('-3 week -1 day'));

        $dates_array['w3_start'] = date('Y-m-d', strtotime('-2 week  -7 days'));
        $dates_array['w3_end']   = date('Y-m-d', strtotime('-2 week  -1 day'));
        $total_sold3               = Order::whereRaw('(completed_at between DATE("' . $dates_array['w3_start'] . '") and DATE("' . $dates_array['w3_end'] . '"))')->get()->count();

        $label_sold3 = date('m-d', strtotime('-2 week -7 days')) . '/' . date('m-d', strtotime('-2 week -1 day'));

        $dates_array['w2_start'] = date('Y-m-d', strtotime('-1 week  -7 days'));
        $dates_array['w2_end']   = date('Y-m-d', strtotime('-1 week -1 day'));
        $total_sold2               = Order::whereRaw('(completed_at between DATE("' . $dates_array['w2_start'] . '") and DATE("' . $dates_array['w2_end'] . '"))')->get()->count();

        $label_sold2 = date('m-d', strtotime('-1 week -7 days')) . '/' . date('m-d', strtotime('-1 week -1 day'));

        $dates_array['w1_start'] = date('Y-m-d', strtotime('- 7 days'));
        $dates_array['w1_end']   = date('Y-m-d', );
        $total_sold1               = Order::whereRaw('(completed_at between DATE("' . $dates_array['w1_start'] . '") and DATE("' . $dates_array['w1_end'] . '"))')->get()->count();
        $label_sold1               = date('m-d', strtotime('Monday')) . '/' . date('m-d', strtotime('Sunday'));

        $result = [
            'totals' => [

                $total_sold5,
                $total_sold4,
                $total_sold3,
                $total_sold2,
                $total_sold1
            ],
            'labels' => [
                $label_sold5,
                $label_sold4,
                $label_sold3,
                $label_sold2,
                $label_sold1
            ]
        ];

        return json_encode($result);
    }
}

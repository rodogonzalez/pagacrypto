<?php
namespace App\Helpers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;
use Cookie;
use Request;

class Balance
{

    public static function get_balance_available(){
        $current_user = backpack_user();
        if (is_null($current_user)) {
            return;
        }

        $orders = \DB::table('orders')
            ->select([DB::raw('round(sum(order_items.price),2) as amount_available')])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.users_id', $current_user->id)
            ->where('orders.status', 'completed')
            ->whereraw('orders.id not in (select orders_id as id from store_withdrawal_orders) ')
            ->first();

        if (is_null($orders->amount_available)){
            return 0;
        }

        return $orders->amount_available;

    }

    public static function get_orders_unprocessed(){
        $current_user = backpack_user();
        if (is_null($current_user)) {
            return;
        }
        //$orders = Order::where('status','completed')->get();
        $orders = \DB::table('orders')
            ->select([DB::raw('orders.id'),DB::raw('round(sum(order_items.price * order_items.quantity),2) as amount')])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.users_id', $current_user->id)
            ->where('orders.status', 'completed')
            ->whereraw('orders.id not in (select orders_id as id from store_withdrawal_orders) ')
            ->groupBy('orders.id')
            ->get();

        return $orders;

    }
}

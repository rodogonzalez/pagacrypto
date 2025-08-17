<?php
namespace App\Helpers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Cookie;
use Request;

class Cart
{

    private static $order = null;

    public  static  function get_cart(){
        return self::$order;



    }




    public static function set_cart($stores_id){


        $user_id      = 0;
        $current_user = backpack_user();

        if (!is_null($current_user)) {
            $user_id = $current_user->id;
        }

        // create order associated with the cart
        $order = Order::create(['users_id' => $user_id, 'stores_id' => $stores_id, 'currency' => env('DEFAULT_COIN')]);
        self::$order = $order;

        return $order;

    }



}

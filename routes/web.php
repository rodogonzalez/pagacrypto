<?php
/*
use App\Helpers\BlockBee;
use App\Helpers\WalletObserver;
use App\Models\CryptoOrderPayment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
*/

use App\Http\Controllers\IndexController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;




Route::group([
    'prefix'    => '/',
    'namespace' => 'App\Http\Controllers',
], function () {

    //Route::get('/', [IndexController::class, 'index']);
    //Route::get('/', [IndexController::class, 'indexAdmin']);

    Route::get('/', function () {
          return redirect('/admin');

    });


    Route::get('/support', [IndexController::class, 'supportLanding']);
    Route::get('/stats', [StatsController::class, 'generate_store_category_stats']);
    Route::post('process-payment', [PaymentController::class, 'generate_payment_order']);
    Route::get('pay/{order}', [PaymentController::class, 'pay_order'])->name('pay');
    Route::get('google-payment/{order}', [PaymentController::class, 'google_pay_order'])->name('google.payment-link');
    Route::get('blockbee-callback/{order_id}', [PaymentController::class, 'blockbee_callback'])->name('blockbee_callback');
    Route::get('payment-status/{wallet}/{order_id}', [PaymentController::class, 'get_order_status'])->name('payment_status');
});

Route::group([
    'prefix'    => '/api',
    'namespace' => 'App\Http\Controllers',
], function () {

    Route::post('/process-payment/{order_id}', [PaymentController::class, 'process_payment_order']);
    Route::get('/payment-status/{order_id}', [PaymentController::class, 'check_order_status'])->name('check_payment_status');


    Route::get('/get-locals', function () {
        return response()->json(\App\Models\Local::all());
    });
    Route::get('/search/products/code/{store_id}/{name}', function ($store_id, $code) {
        if ($code == '') {
            return response()->json(\App\Models\Product::where('stores_id', $store_id)->get());
        } else
            return response()->json(\App\Models\Product::where('stores_id', $store_id)->where('barcode', 'like', "$code%")->get());
    });
    Route::get('/search/products/{store_id}/bar-code/{code}', function ($store_id, $code) {
        return response()->json(\App\Models\Product::where('stores_id', $store_id)->where('barcode', $code)->first());
    });
    Route::get('/search/products/term/{store_id}/{name}', function ($store_id, $name) {
        if ($name == '') {
            return response()->json(\App\Models\Product::where('stores_id', $store_id)->get());
        } else
            return response()->json(\App\Models\Product::where('stores_id', $store_id)->where('name', 'like', "%$name%")->get());
    });
});

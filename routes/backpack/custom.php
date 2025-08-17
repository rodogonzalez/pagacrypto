<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\IndexController;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group(
    [
        'prefix'     => config('backpack.base.route_prefix', 'admin'),
        'namespace'  => 'App\Http\Controllers\Admin',
        'middleware' => array_merge(
            (array) config('backpack.base.web_middleware', 'web'),
                    (array) config('backpack.base.middleware_key', 'admin')
        ),
    ],
    function () {

        // custom admin routes
        Route::get('/', [IndexController::class, 'indexAdmin']);
        Route::get('dashboard', [IndexController::class, 'indexAdmin']);

        Route::get('pos/{id}', [IndexController::class, 'show_pos_dashboard']);
        Route::get('account/withdrawls/', [IndexController::class, 'WithDrawlsRequests'])->name('user_withdrawls');
        Route::get('account/withdrawls/request', [IndexController::class, 'WithDrawlsRequests_step1'])->name('user_withdrawls_step1');
        Route::get('account/withdrawls/process', [IndexController::class, 'WithDrawlsRequests_process'])->name('user_withdrawls_step2');
        Route::get('order/view/{id}', [IndexController::class, 'show_order']);



        Route::crud('order', 'OrderCrudController');
        Route::crud('user', 'UserCrudController');

        Route::crud('payment', 'PaymentCrudController');
        Route::crud('setting', 'SettingCrudController');

        Route::crud('wallet', 'WalletCrudController');
        Route::crud('crypto-order-payment', 'CryptoOrderPaymentCrudController');
        Route::crud('withdrawl', 'WalletCrudController');
        Route::crud('withdrawl', 'WithdrawlCrudController');
        Route::crud('bank', 'BankCrudController');

    }
); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */

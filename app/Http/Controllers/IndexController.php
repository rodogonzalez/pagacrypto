<?php

namespace App\Http\Controllers;

use App\Helpers\BlockBee;
use App\Helpers\WalletObserver;
use App\Models\CryptoOrderPayment;

use App\Models\Order;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class IndexController extends Controller
{
    //

    public function index()
    {
        $objStats         = new \App\Http\Controllers\StatsController;
        $categories_stats = $objStats->generate_store_category_stats();
        return view('index', ['categories_stats' => $categories_stats]);
    }

    public function indexAdmin()
    {
        if (!\Auth::check()) {
            return $this->index();
        }

        return view('admin.welcome');


    }


    public function show_order( $id )
    {
        $order              = Order::whereRaw("id =  '{$id}'")->first();


        $payment_controller = new \App\Http\Controllers\PaymentController();

        $blockchain_deposits = [];
        $blockchain_status   = null;

        if (!is_null($order->crypto_wallet_transaction)) {
            //$blockchain_status = $payment_controller->get_order_status($order->crypto_wallet_transaction, $order->id, false);
            $blockchain_status = $payment_controller->get_blockchain_transactions($order->crypto_wallet_transaction, $order);
            //dd(($blockchain_status),$order->crypto_wallet_transaction);
            if (isset($blockchain_status['wallet_status'])) {

                foreach ($blockchain_status['wallet_status'] as $block) {

                    switch ($block['blockNumber']) {
                        case null:
                            foreach ($block['outputs'] as $output) {
                                if ($output['address'] == $order->crypto_wallet_transaction) {
                                    //dd($block,$output,$order->crypto_wallet_transaction,$output['coin']);
                                    $block['status']       = 'Processing';
                                    $blockchain_deposits[] = $block;
                                }
                            }
                            break;

                        default:

                            foreach ($block['outputs'] as $output) {
                                if ($output['address'] == $order->crypto_wallet_transaction) {
                                    //dd($block,$output,$order->crypto_wallet_transaction,$output['coin']);
                                    $block['status']       = 'Confirmed - out';
                                    $blockchain_deposits[] = $block;
                                }
                            }
                    }
                }
            }
        }
        $block_chain_explorer = "https://blockchair.com/litecoin/transaction/";
        $block_chain_wallet_explorer = "https://blockchair.com/litecoin/address/";
        return view('order.view', ['order' => $order, 'store' => $store, 'products' => $products, 'blockchain_deposits' => $blockchain_deposits, 'block_chain_explorer' => $block_chain_explorer, 'block_chain_wallet_explorer'=>$block_chain_wallet_explorer]);
    }

    // TODO: url signed
    public function WithDrawlsRequests()
    {
        return view('admin.user_withdrawl');
    }

    public function WithDrawlsRequests_step1()
    {
        return view('admin.withdrawls.step1');
    }

    public function WithDrawlsRequests_process()
    {
        $current_user = backpack_user();
        if (is_null($current_user)) {
            abort(403);
        }

        $user_id = $current_user->id;
        $amount = \App\Helpers\Balance::get_balance_available();

        $withdrawal = \App\Models\Withdrawl::create(
            [
                'users_id'            => $user_id,
                'amount'              => $amount,
                'iban_account'        => 'test',
                'bank_name'           => 'test',
                'account_owner_name'  => 'test',
                'status'              => 'requested',
                'bank_transaction_id' => 0,
                'notes'               => ''
            ]
        );

        foreach (\App\Helpers\Balance::get_orders_unprocessed() as $order) {
            \App\Models\WithdrawlOrder::Create([
                'store_withdrawal_id' => $withdrawal->id,
                'orders_id'           => $order->id
            ]);
        }

        return view('admin.withdrawls.confirmation', ['withdrawal' => $withdrawal]);
    }


    public function supportLanding()
    {
        return view('support');
    }

    public function show_deposit(){

        return view('order.deposit', []);
    }
}

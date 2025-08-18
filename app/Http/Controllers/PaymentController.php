<?php

namespace App\Http\Controllers;

use App\Helpers\BlockBee;
use App\Helpers\WalletObserver;
use App\Models\CryptoOrderPayment;
use App\Models\Order;
use App\Models\Local;
use App\Models\OrderItem;
use App\Models\Product;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Mail\OrderCompleted;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function process_payment_order( $order_id, Request $request )
    {
        $input                    = $request->all();
        $order                    = Order::find($order_id);
        $coin                     = $input['xcoin'];
        $order->currency          = $coin;
        $amount                   = $input['amount'];
        $order->fiat_total_amount = $amount;
        $amount                   = 0.4;
        $conversion               = BlockBee::get_convert($coin, $amount, env('COINPAYMENT_CURRENCY'), env('BLOCKBEE_API'));

        $order->crypto_wallet_total_amount = $conversion->value_coin;
        $order->save();
        return $this->pay_order($order);
    }

    public function generate_payment_order( Request $request )
    {
        $input        = $request->all();
        $user_id      = 0;
        $current_user = backpack_user();
        $coin         = $input['xcoin'];

        if (!is_null($current_user)) {
            $user_id = $current_user->id;
        }

        $order       = Order::create(['users_id' => $user_id, 'stores_id' => $input['stores_id'], 'currency' => $input['xcoin']]);
        $count_index = 0;

        foreach ($input['id'] as $item) {
            $qty        = $input['qty'][$count_index];
            $price      = Product::find($item)->price;
            $order_item = OrderItem::create(['order_id' => $order->id, 'quantity' => $qty, 'product_id' => $item, 'price' => $price]);
            $count_index++;
        }

        $amount = $order->total();

        if ($coin == 'g-pay') {

            $url = Url::temporarySignedRoute('google.payment-link', now()->addMinutes(5), ['order' => $order, 'user' => 34, 'response' => 'yes']);

            return redirect($url);
        }

        // if not google pay, it is crypto payment
        $conversion                        = BlockBee::get_convert($coin, $amount, env('COINPAYMENT_CURRENCY'), env('BLOCKBEE_API'));
        $order->crypto_wallet_total_amount = $conversion->value_coin;
        $order->save();

        $url = Url::temporarySignedRoute('pay', now()->addMinutes(10), ['order' => $order]);
        return redirect($url);

        //return redirect( route() );
    }

    public function google_pay_order( Order $order, Request $request )
    {
        // security check?
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $options = new QROptions(
            [
                'eccLevel'   => QRCode::ECC_L,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'version'    => QRCode::VERSION_AUTO,
            ]
        );

        $this_url    = $request->fullUrl();
        $this_url_qr = (new QRCode($options))->render($this_url);

        return view('order.googlepay', ['order' => $order, 'this_url_qr' => $this_url_qr]);

    }

    public function pay_order( Order $order )
    {

        date_default_timezone_set('America/Costa_Rica');
        $amount      = $order->fiat_total_amount;
        $amount      = 0.4;
        $seconds     = ((60 * 10) + 2);
        $expire_time = date('M d Y H:i:s', strtotime("$seconds seconds"));
        $coin        = $order->currency;
        $store       = 0;
        $my_address  = env('BLOCKBEE_WALLET_ADDRES');

        switch ($coin) {
            case 'bch':
                $my_address =  env('BCH_BLOCKBEE_WALLET_ADDRES') ;
                break;

            case 'ltc':
                $my_address =  env('LTC_BLOCKBEE_WALLET_ADDRES') ;
                break;

            case 'doge':

                $my_address =  env('DOGE_BLOCKBEE_WALLET_ADDRES') ;
                break;

        }

        $callback_url = route('blockbee_callback', ['order_id' => md5($order->id)]);
        $parameters   = ['order' => $order->id, 'amount' => $order->crypto_wallet_total_amount];
        $size         = 400;

        $blockbee_params = [];
        $bb              = new BlockBee($coin, $my_address, $callback_url, $parameters, $blockbee_params, env('BLOCKBEE_API'));
        $payment_address = $bb->get_address();
        $options         = new QROptions(
            [
                'eccLevel'   => QRCode::ECC_L,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'version'    => 5,
            ]
        );

        //$qrcode             = ( new QRCode( $options ) )->render( $payment_address );
        $qrcode_with_amount               = (new QRCode($options))->render("{$payment_address}?amount={$order->crypto_wallet_total_amount}");
        $order->crypto_wallet_transaction = $payment_address;
        $order->save();
        return view('order.pay-popup', ['order' => $order, 'wallet_addr' => $payment_address, 'qr' => $qrcode_with_amount, 'amount' => $order->crypto_wallet_total_amount, 'fiat_amount' => $amount, 'expires' => $expire_time, 'callback' => $callback_url]);
        //return view('order.pay', ['order' => $order, 'products' => $products, 'wallet_addr' => $payment_address, 'qr' => $qrcode_with_amount, 'amount' => $order->crypto_wallet_total_amount, 'fiat_amount' => $amount, 'expires' => $expire_time, 'callback' => $callback_url]);
    }

    public function blockbee_callback( string $order_id )
    {
        $Order = Order::whereRaw('md5(id)="' . $order_id . '"')->first();

        if (is_null($Order)) {
            abort(403);
        }

        $order_id                 = $Order->id;
        $payment_data             = BlockBee::process_callback($_GET);
        $new_payment_log          = CryptoOrderPayment::create(['order_id' => $order_id, 'transaction_id' => $payment_data['txid_in'], 'amount_received' => ($payment_data['value_forwarded_coin'] + $payment_data['fee_coin']), 'fee' => $payment_data['fee_coin'], 'payment_details' => json_encode($payment_data)]);
        $crypto_wallet_total_paid = $Order->crypto_paid_total();
        Log::info('Payment Received ' . $order_id . ' ' . print_r($payment_data, true));

        if ($crypto_wallet_total_paid >= $Order->crypto_wallet_total_amount) {
            // it had received the amount required to set the order as paid
            $Order->status       = 'completed';
            $Order->completed_at = now();
            $Order->save();
            Log::info('Order Completed ' . $Order->id);



            //$datos = ['nombre' => 'Rodolfo'];
            Mail::to('rodogonzalez@msn.com')->send(new OrderCompleted($Order));
            Log::info('Email Sent to  ' . $Order->id);
        }

        return json_encode(['status' => 'ok', 'received' => $crypto_wallet_total_paid]);
    }

    public function get_blockchain_transactions( $wallet, $Order )
    {

        $wallet_helper            = new WalletObserver();
        $currency                 = $Order->currency;
        $crypto_wallet_total_paid = $Order->crypto_paid_total();
        $wallet_status            = $wallet_helper->getAddressData($wallet, $currency);
        $payment_detected         = false;
        $amount_detected          = 0;
        $trans_Amount             = 0;

        if (!is_null($wallet_status)) {
            $transactions = $wallet_status;
            foreach ($transactions as $transaction) {
                foreach ($transaction['outputs'] as $output) {
                    if ($output['address'] == $wallet) {
                        $trans_Amount    = $trans_Amount + $output['value'];
                        $amount_detected = $trans_Amount;
                    }
                }
                $payment_detected = true;
            }
        }

        $paid_status = false;

        if ($amount_detected >= $Order->crypto_wallet_total_amount) {
            $paid_status = true;
        }


        $response = [
            'paid'             => $paid_status,
            'payment_detected' => $payment_detected,
            'amount_detected'  => $amount_detected,
            'amount_confirmed' => $crypto_wallet_total_paid,
            'amount_required'  => $Order->crypto_wallet_total_amount,
            'wallet_status'    => $wallet_status
        ];

        return $response;
    }

    public function check_order_status( $order_id )
    {

        $Order  = Order::whereRaw("id =  '{$order_id}'")->first();
        $wallet = $Order->crypto_wallet_transaction;
        if (is_null($Order))
            abort(403);

        $response = [];

        $crypto_wallet_total_paid = $Order->crypto_paid_total();

        if ($crypto_wallet_total_paid >= $Order->crypto_wallet_total_amount) {
            $response = ['paid' => true, 'amount_confirmed' => $crypto_wallet_total_paid, 'amount_required' => $Order->crypto_wallet_total_amount, 'payments' => $Order->payments()];
            return json_encode($response);
        }

        $response = $this->get_blockchain_transactions($wallet, $Order);
        return json_encode($response);

    }


    public function get_order_status( string $wallet, $order_id, $encoded = true )
    {

        $Order = Order::whereRaw("id =  '{$order_id}'")->first();
        if (is_null($Order))
            abort(403);

        $response = [];

        $crypto_wallet_total_paid = $Order->crypto_paid_total();

        if ($crypto_wallet_total_paid >= $Order->crypto_wallet_total_amount) {
            $response = ['paid' => true, 'amount_confirmed' => $crypto_wallet_total_paid, 'amount_required' => $Order->crypto_wallet_total_amount, 'payments' => $Order->payments()];
            return json_encode($response);
        }

        $response = $this->get_blockchain_transactions($wallet, $Order);

        if ($encoded) {
            return json_encode($response);
        } else {

            return $response;
        }
    }
}

@extends(backpack_view('plain'))

@section('content')
<?php
$store = \App\Models\Local::where('id', $order->stores_id)->first();
$block_chain_explorer = "https://blockchair.com/litecoin/transaction/";
$block_chain_wallet_explorer = "https://blockchair.com/litecoin/address/";

?>
    <div id="order_alive" class="container ">
        <h1>{{ __('messages.payment.title') }} {{ __('messages.order.title') }} : {{ $order->id }}</h1>
        <hr>

        <h1><img src="{{env ('APP_URL')}}{{$store->thumb}}" style="display:inline; width:100px;">{{$store->name}}</h1>
        <div id="payment_details" class='row '>
            <div class="col-12 card align-text-bottom">
                <div class="row small">
                    <div class="col-6">
                        <b>Status : {{ucfirst($order->status)}} </b>
                    </div>
                    <div class="col-6 text-right">
                        {{$order->completed_at}}


                    </div>

                </div>
                <hr>
                <table>
                    <tr>
                        <td>
                            Tienda / Comercio : <br>
                            Direccion Destino:<br>
                            Monto Crypto: <br>
                            Currency :<br>
                            Total USD :<br>

                        </td>
                        <td>
                            {{$store->name}}<br>
                            <a href="{{$block_chain_wallet_explorer}}{{$order->crypto_wallet_transaction}}" target="_blank">{{$order->crypto_wallet_transaction}}</a><br>
                            {{$order->crypto_wallet_total_amount}}<br>
                            {{$order->currency}}<br>
                            {{$order->total()}}

                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
@endsection

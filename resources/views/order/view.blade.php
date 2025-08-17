@extends(backpack_view('blank'))

@section('content')

    <div id="order_alive" class="container ">
        <h1>{{ __('messages.payment.title') }} {{ __('messages.order.title') }} : {{ $order->id }}</h1>
        <hr>

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

                <div class="cart  row">
                    <div class='col-4 '>
                        Tienda / Comercio : <br>
                        Direccion Destino:<br>
                        Monto Crypto: <br>
                        Currency :<br>
                        Total USD :<br>
                    </div>
                    <div class='col-8'>
                        {{$store->name}}<br>
                        <a href="{{$block_chain_wallet_explorer}}{{$order->crypto_wallet_transaction}}" target="_blank">{{$order->crypto_wallet_transaction}}</a><br>
                        {{$order->crypto_wallet_total_amount}}<br>
                        {{$order->currency}}<br>
                        {{$order->total()}}


                    </div>
                </div>
                <hr>
                <div class="container text-center">
                    <div id="app">
                        <order-component  :order="{{$order->toJson()}}" :products="{{$products->toJson()}}" :expanded="true"/>
                    </div>
                </div>

            </div>
            <div class="card mb-4 mt-4 system">
            <h2>Transacciones </h2>

            @foreach ($blockchain_deposits as $payment)
                <div class="row">
                    <div class="col-6"><a href="{{ $block_chain_explorer }}{{ $payment["hash"] }}" target="_blank">{{ $payment["hash"] }}</a></div>
                    <div class="col-3">{{ $payment["inputs"][0]["coin"]["value"] }} {{$order->currency}}</div>
                    <div class="col-3">{{ $payment["status"] }}</div>

                </div>
            @endforeach

            @if (backpack_user()->hasRole('admin'))
                    <hr>
                    <h2>Deposito Internos</h2>

                    @foreach ($order->payments() as $payment)
                        <div class="row">
                            <div class="col-3">{{ $payment->amount_received }}{{$order->currency}}</div>
                            <div class="col-9"><a href="{{ $block_chain_explorer }}{{ $payment->transaction_id }}" target="_blank">{{ $payment->transaction_id }}</a></div>

                        </div>
                    @endforeach


            @endif
            </div>



        </div>
    </div>
@endsection

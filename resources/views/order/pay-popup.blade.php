@extends(backpack_view('plain'))

@section('content')

<div id="order_alive" class="container card">
    <a id="payment_thanks" class="container " href="#" style="display:none;">
        <div class="row">
            <div class="col-2">
                <img src="/images/success.png" class="">
            </div>
            <div class="col-10">
                <h1>Pago Recibido</h1>
                <span>Le informamos que su pago ha sido realizado satisfactoriamente. Agradecemos su confianza y preferencia. Si tiene alguna duda o necesita asistencia adicional, no dude en contactarnos.

                <h3>Total : ${{ $fiat_amount }} USD</h3>
                </span>
            </div>
        </div>
    </a>

    <div class='row system'>
        <div id="payment_details" class="col-12   align-text-bottom">

            <div class="container text-center">
                <div class="row">
                    <div class="col-4 text-center">
                        <img src="{{ $qr }}" style="max-width:320px; display:block;">
                    </div>

                    <div class="col-8">
                        <table class="">
                            <tr>
                                <td class="text-start">Orden</td>
                                <td>&nbsp;</td>
                                <td class="text-start">{{ $order->id }}</td>
                            </tr>

                            <tr>
                                <td class="text-start">Direccion Destino</td>
                                <td>&nbsp;</td>
                                <td class="text-start">{{ $wallet_addr }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Amount</td>
                                <td>&nbsp;</td>
                                <td class="text-start">{{ $amount }}{{ strtoupper( $order->currency ) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Fiat Equivalent</td>
                                <td>&nbsp;</td>
                                <td class="text-start">${{ $fiat_amount }} USD</td>
                            </tr>
                        </table>

                        <div id="process_payment" class="text-center mt-4 align-middle">
                            <h4><img src="/images/processing.gif" style="width:100px; height:100px;"> Esperando pago ... </h4>
                        </div>

                        <div id="payment_detected" class=" text-center" style="display:none;">
                            <div class="row">
                                <div class="col-4"><img src="/images/alerta.png" style="max-height:100px;object-fit:contain;"></div>
                                <div class="col-8">
                                    <h2 class="container blink_me">Pago Detectado</h2>
                                    <h3 id="amount_detected" class=" "></h3>

                                </div>
                            </div>
                        </div>

                    </div>




                </div>
                <h1 id="timer_container" class="system"></h1>
            </div>
        </div>
    </div>
</div>
@endsection

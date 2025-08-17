@extends(backpack_view('blank'))

@section('content')
<script>
    // Set the date we're counting down to
    var countDownDate = new Date("{{$expires}}").getTime();

    let timer_on = true;

    // Update the count down every 1 second
    var x = setInterval(function() {
        if (!timer_on) return;
        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);


        if (minutes.toString().length == 1) {
            minutes = "0" + minutes;
        }
        if (seconds.toString().length == 1) {
            seconds = "0" + seconds;
        }


        // Display the result in the element with id="demo"
        document.getElementById("timer_container").innerHTML = minutes + ":" + seconds;

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("order_alive").innerHTML = "<h1>Orden Expirada</h1>";
            timer_on = false;
        }
    }, 1000);


    /*TODO: Move this code inside to a compiled JS file to be mixed */
    function check_payment_status() {
        var url = "{{ route('payment_status', ['wallet' => $wallet_addr, 'order_id' => $order->id]) }}";
        fetch(url)
            .then(response => response.json())
            .then(json => {
                if (json.paid) {
                    //redirect
                    $("#payment_details").hide();
                    $("#payment_thanks").show();
                    timer_on = false;
                    return;
                } else {
                    if (json.payment_detected) {
                        $("#process_payment").hide();
                        $("#payment_detected").show();
                        $("#amount_detected").text("Monto detectado " + json.amount_detected + " {{ strtoupper(env('BLOCKBEE_COIN')) }}");

                    }
                };
            });
        time_W = 5000;
        setTimeout(check_payment_status, time_W);
    }
    check_payment_status();
</script>


<div id="order_alive" class="container card">

    <h1>{{ __('messages.payment.title') }} {{ __('messages.order.title') }} : {{ $order->id }}</h1>
    <hr>



    <a id="payment_thanks" class="container card" href="#" style="display:none;">
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

    <div class='row '>
        <div id="payment_details" class="col-6 card align-text-bottom">
            <div class="cart  row">
                <div class='col-4 '>

                    Direccion Destino:<br>
                    Amount :<br>
                    Exchange Rate :<br>
                    Fiat Equivalent :<br>
                </div>
                <div class='col-8'>
                    <small> {{ $wallet_addr }}</small><br>
                    {{ $amount }} {{ strtoupper( $order->currency ) }}<br>
                    ${{ $fiat_amount }} USD<br>


                </div>
            </div>
            <hr>
            <div class="container text-center">
                <div class="row">
                    <div class="col-4 text-center">
                        <img src="{{ $qr }}" style="max-width:320px; display:block;">
                    </div>
                    <div id="process_payment" class="col-8 text-center">
                        <h4><img src="/images/processing.gif" style="width:100px; height:100px;"> Esperando pago ... </h4>
                    </div>

                    <div id="payment_detected" class="container card col-8 text-center" style="display:none;">
                        <div class="row">
                            <div class="col-4"><img src="/images/alerta.png" style="max-height:100px;object-fit:contain;"></div>
                            <div class="col-8">
                                <h2 class="container blink_me">Pago Detectado</h2>
                                <h3 id="amount_detected" class=" "></h3>

                            </div>

                        </div>




                    </div>

                </div>



                <h1 id="timer_container" class="system"></h1>


            </div>

        </div>

        <div id="app" >
            <order-component :order="{{$order->toJson()}}" :products="{{$products->toJson()}}" :expanded="true" />
        </div>

    </div>
</div>
@endsection

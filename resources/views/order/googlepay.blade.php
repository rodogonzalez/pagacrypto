@extends(backpack_view('blank'))

@section('content')



<div id="order_alive" class="container ">

    <h1>{{ __('messages.payment.title') }} {{ __('messages.order.title') }} : {{ $order->id }}</h1>
    <hr>





    <div id="payment_details" class='row '>
        <div class="col-6 card align-text-bottom">
            <hr>
            <div class="container text-center">
                <div class="row">
                    <div class="col-4 text-center">
                        <img src="{{ $this_url_qr }}" style="max-width:320px; display:block;">
                    </div>


                </div>


                <div id="buttons">
                    <!-- html fragment -->
<div id="btn-container-pay"></div>

<script>

import GooglePayButton from './node_modules/@google-pay/button-element/dist/index.js';

const paymentsClient =
    new google.payments.api.PaymentsClient({environment: 'TEST'});

    paymentsClient.isReadyToPay(isReadyToPayRequest)
    .then(function(response) {
      if (response.result) {
        // add a Google Pay payment button
      }
    })
    .catch(function(err) {
      // show error in developer console for debugging
      console.error(err);
    });

    function init_payment(){
        alert("listo done :) ");

    }
</script>
                </div>



            </div>

        </div>



    </div>
</div>

<script
  async
  src="https://pay.google.com/gp/p/js/pay.js"
  onload="init_payment()" >
</script>
@endsection

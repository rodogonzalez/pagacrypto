<script setup>

import { ref } from "vue";


const props = defineProps(
                ['wallet','order','amount','exchangerate','fiatamount','cryptocurrency']);

const hola = ref(props.walletaddress);
console.log(props);

/*

//TODO: Move this code inside to a compiled JS file to be mixed 
function check_payment_status() {
    console.log("checking ....");

    var url = '{{ route("payment_status", ["wallet" => $wallet_addr   , "order_id" => $Order->id]); }}';
    fetch(url)
        .then(response => response.json())
        .then(json => {

            console.log(json);
            if (json.paid) {
                //redirect 
                $("#payment_details").hide();
                $("#payment_thanks").show();
                return;


            } else {
                if (json.payment_detected) {



                }


            };


        });
    time_W = 5000 * 3;

    setTimeout(check_payment_status, time_W);
}

check_payment_status();
*/
</script>
<template>
    HOLA: {{ hola }}
    <a id="" class="container card" href="#" style="display:none;">
        <div class="row">
            <div class="col-5">
                <img src="/images/success.gif" class="">
            </div>
            <div class="col-7">
                <h1>Pago Recibido</h1>
                <h2>pronto estaremos contactando para la entrega.</h2>
            </div>
        </div>
    </a>

    <div id="" class='row '>
        <div class="col-6 card align-text-bottom">
            <div class="cart  row">
                <div class='col-4 '>

                    Direccion Destino:<br>
                    Amount :<br>
                    Exchange Rate :<br>
                    Fiat Equivalent :<br>
                </div>
                <div class='col-8'>
                    <small> {{ props.wallet_addr }}</small><br>
                    {{ props.amount }} {{ props.cryptocurrency }}<br>
                    ${{ props.exchange_rate }} usd.<br>
                    ${{ props.fiat_amount }} usd<br>
                    <div id="detected">
                        Pago detectado


                    </div>

                </div>

            </div>
            <hr>
            <div class="container text-center">
                <h2><img src="/images/processing.gif" style="width:100px; height:100px;"> Esperando pago ... </h2>
            </div>

        </div>

        

    </div>
</template>
<script setup>
import Swal from "sweetalert2";
import { ref, reactive, onMounted } from "vue";

import fxBeepOK from "./sounds/ok-beep.mp3";
import fxBeepErr from "./sounds/error.mp3";

const props = defineProps(["order_id"]);
const SoundBeepOk = new Audio(fxBeepOK);
const SoundBeepErr = new Audio(fxBeepErr);
const refresh_rate = 1000;
const token_value = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
const csrf = ref(token_value);

const order = reactive({
  total: 0,
  status: "incomplete",
  payment_status: "",

});

let deposit_amount = ref(100);
function playAudio() {
  SoundBeepOk.play();
}

function playErrAudio() {
  SoundBeepErr.play();
}


/*TODO: Move this code inside to a compiled JS file to be mixed */
function check_payment_status() {
  if (order.payment_status == "paid") return;

  var url = "/api/payment-status/" + props.order_id;
  fetch(url)
    .then(response => response.json())
    .then(json => {
      if (json.paid) {
        //redirect
        playAudio();
        order.payment_status = "paid";
        $("#payment_details").hide();
        $("#payment_thanks").show();
        setTimeout(refresh_pos_page, 10000);
        return;
      } else {
        if (json.payment_detected) {
          $("#process_payment").hide();
          $("#payment_detected").show();
          $("#amount_detected").text("Pago Detectado " + json.amount_detected);
        }
      }
    });

  setTimeout(check_payment_status, 500);
}

function show_payment_popup() {

  Swal.showLoading();
  axios({
    method: "post",
    url: "/api/process-payment/" + props.order_id,
    data: {
      amount: document.getElementById("deposit_amount").value,
      xcoin: document.getElementById("xcoin").value
    }
  }).then(function(response) {
    console.clear();
    order.payment_status = "paying";
    //    console.log(response.data);
    //response.data.pipe(fs.createWriteStream('ada_lovelace.jpg'))

    check_payment_status();

    Swal.fire({
      title: "Procesamiento de Pago",
      html: response.data,
      width: "60vw",
      showConfirmButton: false
      //,            toast: true
      //confirmButtonText: "Cool"
    });
  });
}

onMounted(() => {

});
</script>
<template>
  <div class=" ">


    <h1>Cuanto desea depositar ? </h1>
        <input id="deposit_amount" v-model="deposit_amount" >
         <select id="xcoin" name="xcoin">
            <option value="ltc" selected>Litecoin</option>
            <option value="doge">Dogecoin</option>
            <option value="bch">Bitcoin Cash</option>
            <option value="bct">Bitcoin</option>
          </select>
        <button v-on:click="show_payment_popup()" >DEPOSITAR</button>
  </div>
</template>

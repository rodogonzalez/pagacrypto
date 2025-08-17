<script setup>

const props = defineProps(['storeid','currency','products']);

import { ref, reactive, onMounted } from "vue";
import ProductsListComponent from "../components/products-list.vue";


import Quagga from "quagga"; // ES6
import fxBeepOK from "./sounds/ok-beep.mp3";
import fxBeepErr from "./sounds/error.mp3";

const SoundBeepOk = new Audio(fxBeepOK);
const SoundBeepErr = new Audio(fxBeepErr);
const refresh_rate = 1000;


const this_store = reactive({
  products: props.products,
});
const bar_code = ref();
const search_term = ref();

const cart = reactive({
  total: 0,
  status: "incomplete",
  products: [],
});

function reset_search_term() {
  search_term.value = "";
  bar_code.value = "";
  this_store.products = props.products;
}

function open_camera() {
  Quagga.init(
    {
      frequency: refresh_rate,
      locate: true,
      inputStream: {
        name: "Live",
        type: "LiveStream",
        target: document.querySelector("#preview_camera"), // Or '#yourElement' (optional)
      },
      decoder: {
        readers: [
          "code_128_reader",
          "ean_reader",
          "ean_8_reader",
          "code_39_reader",
          "code_39_vin_reader",
          "codabar_reader",
          "upc_reader",
          "upc_e_reader",
          "i2of5_reader",
          "2of5_reader",
          "code_93_reader",
        ],
      },
    },
    function (err) {
      if (err) {
        console.log(err);
        return;
      }
      Quagga.start();
    }
  );
}

function playAudio() {
  SoundBeepOk.play();
}

function playErrAudio() {
  SoundBeepErr.play();
}

Quagga.onDetected(function (result) {
  var code = result.codeResult.code;
  bar_code.value = code;
  Quagga.stop();

  axios.get("/find/products/" + props.storeid + "/bar-code/" + code).then((res) => {
    //local_actual.products = res.data;
    //console.log(res.data);
    let item = res.data;
    //console.log(item);
    if (item.id) {
      playAudio();
      addToCart(item);
      bar_code.value = "";
    } else {
      playErrAudio();
    }
    setTimeout(open_camera, refresh_rate);
  });
});


function calculate_total() {

    cart.total = 0;

    function calculate_amount(item_cursor, index, array){
        cart.total = cart.total + item_cursor.qty * item_cursor.price;
    }

    cart.products.forEach(calculate_amount);
    cart.total = parseFloat(cart.total).toFixed(2);
}

const addToCart = (item) => {
  let product_is_in_cart = false;
  let product_index = 0;
  let total = 0;
  let item_to_push = {
    id: item.id,
    name: item.name,
    price: item.price,
    qty: 1,
  };

  function search_item(item_cursor, index, array) {
    if (item.id == item_cursor.id) {
      product_is_in_cart = true;
      product_index = index;
    }
    cart.total = item_cursor.qty * item_cursor.price;
  }

  cart.products.forEach(search_item);

  if (!product_is_in_cart) {
    cart.products.push(item_to_push);
  } else {
    cart.products[product_index].qty++;
  }

  calculate_total();
  reset_search_term();
};

const remove_from_cart = (item_id) => {
  let new_product_list = [];
  let n_index = null;
  function getIndex(item_cursor, index, array) {
    if (item_cursor.id != item_id) {
      new_product_list.push(item_cursor);
    }
  }
  cart.products.forEach(getIndex);
  cart.products = new_product_list;
  calculate_total();
};

function search_by_name() {
  if (search_term.value == "") {
    this_store.products = props.products;
  } else {
    axios
      .get("/find/products/term/" + props.storeid + "/" + search_term.value)
      .then((res) => {
        this_store.products = res.data;
      });
  }
}

function search_by_code() {
  if (bar_code.value == "") {
    this_store.products = props.products;
  } else {
    axios
      .get("/api/search/products/code/" + props.storeid + "/" + bar_code.value)
      .then((res) => {
        this_store.products = res.data;
      });
  }
}

function update_cart_item(item_id) {
  const current_item_value = document.getElementById("cart_item_" + item_id).value;

  let new_product_list = [];
  let n_index = null;
  function setNewValue(item_cursor, index, array) {
    if (item_cursor.id == item_id) {
      item_cursor.qty = current_item_value;
      if (current_item_value != 0) new_product_list.push(item_cursor);
    } else {
      new_product_list.push(item_cursor);
    }
  }
  cart.products.forEach(setNewValue);
  cart.products = new_product_list;



  if (current_item_value == 0) {
    return remove_from_cart(current_item_value);
  }
  calculate_total();

}

onMounted(() => {
  open_camera();
});
</script>
<template>
  <div class="container">
    <div class="row">
      <div class="col-5">
        <div id="cart" class="cart">
          <form action="/process-payment" id="pay-form" method="get">
            
            <div class="row cart_payment">
                <div class="col-6 cart_payment_total">Total: $ {{ cart.total }}</div>
                <div class="col-6 cart_payment_action"><button>Payment</button></div>
            </div>


          <ul class=" product_pos_list">
            <li class="row product_pos_cart_item cart_header">
              <div class="col-6 product_name">Nombre</div>
              <div class="col-2  product_price">Precio</div>
              <div class="col-2 product_qty">Qty</div>
              <div class="col-2">&nbsp;</div>
            </li>

            <li
              v-for="item in cart.products"
              v-bind:key="item.id"
              class="row product_pos_cart_item"
            >
              <div class="col-6 product_name">{{ item.name }}</div>
              <div class="col-2 product_price">{{ item.price }}</div>
              <div class="col-2 product_qty">
                <input
                  name="id[]"
                  :value="item.id"
                  type="hidden" readonly="readonly"              
                />
                <input
                  name="qty[]" 
                  :id="'cart_item_' + item.id"
                  :value="item.qty"
                  type="number"                  
                  v-on:keyup="update_cart_item(item.id)"
                />                
              </div>
              <div class="col-2">
                <span v-on:click="remove_from_cart(item.id)"> <i class="la  la-trash"></i></span>
              </div>
            </li>
          </ul>
        </form>
        </div>
      </div>
      <div class="col-7 ">
        <div id="local_details" class="">
          <div class="row">
            <div class="col-10">
              <div class="row">
                <div class="col-6">
                  Codigo :
                  <input
                    v-model="bar_code"
                    placeholder="Digite el codigo o escaneelo"
                    v-on:keyup="search_by_code"
                  />
                </div>
                <div class="col-6">
                  Nombre :
                  <input
                    placeholder="Digite el nombre"
                    v-model="search_term"
                    v-on:keyup="search_by_name"
                  />
                </div>
              </div>
            </div>
            <div class="col-2"><div id="preview_camera"></div></div>
          </div>
          <div class="row ">
            <div class="  store-products">
              <products-list-component
                :store_id="storeid"
                :products="this_store.products"
                @addToCart="addToCart"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

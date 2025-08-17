<script setup>
import Swal from "sweetalert2";
import { ref, reactive, onMounted } from "vue";
import ProductsListComponent from "../products-list.vue";

import Quagga from "quagga"; // ES6
import fxBeepOK from "./sounds/ok-beep.mp3";
import fxBeepErr from "./sounds/error.mp3";

const props = defineProps(["storeid", "name", "currency", "products", "is_mobile", "orderid", "logo"]);
const SoundBeepOk = new Audio(fxBeepOK);
const SoundBeepErr = new Audio(fxBeepErr);
const refresh_rate = 1000;
const token_value = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
const csrf = ref(token_value);
const this_store = reactive({
  id: props.storeid,
  name: props.name,
  products: props.products
});
const bar_code = ref();
const search_term = ref();
const show_camera = ref(true);
const show_search_panel = ref(true);
const hide_on_add_to_cart = ref(false);

const cart = reactive({
  total: 0,
  status: "incomplete",
  payment_status: "",
  products: []
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
        target: document.querySelector("#preview_camera") // Or '#yourElement' (optional)
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
          "code_93_reader"
        ]
      }
    },
    function(err) {
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

Quagga.onDetected(function(result) {
  var code = result.codeResult.code;
  bar_code.value = code;
  Quagga.stop();
  //Swal.showLoading();

  axios.get("/api/search/products/" + props.storeid + "/bar-code/" + code).then(res => {
    let item = res.data;
    //    Swal.hideLoading();

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

  function calculate_amount(item_cursor, index, array) {
    cart.total = cart.total + item_cursor.qty * item_cursor.price;
  }

  cart.products.forEach(calculate_amount);
  cart.total = parseFloat(cart.total).toFixed(2);
}

const addToCart = item => {
  let product_is_in_cart = false;
  let product_index = 0;
  let total = 0;
  let item_to_push = {
    id: item.id,
    name: item.name,
    price: item.price,
    qty: 1
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
  if (hide_on_add_to_cart.value) {
    show_search_panel.value = false;
  }

  calculate_total();
  reset_search_term();
};

const remove_from_cart = item_id => {
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
    axios.get("/api/search/products/term/" + props.storeid + "/" + search_term.value).then(res => {
      this_store.products = res.data;
    });
  }
}

function search_by_code() {
  if (bar_code.value == "") {
    this_store.products = props.products;
  } else {
    axios.get("/api/search/products/code/" + props.storeid + "/" + bar_code.value).then(res => {
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

function update_camera_viewer() {
  show_camera.value = !show_camera.value;
  if (show_camera.value) {
    document.getElementById("preview_camera").style.display = "block";
  } else {
    document.getElementById("preview_camera").style.display = "none";
  }
}

function update_search_viewer() {
  show_search_panel.value = !show_search_panel.value;
}

function refresh_pos_page() {
  window.location.reload(); // Reloads the current page
}

/*TODO: Move this code inside to a compiled JS file to be mixed */
function check_payment_status() {
  if (cart.payment_status == "paid") return;

  var url = "/api/payment-status/" + props.orderid;
  fetch(url)
    .then(response => response.json())
    .then(json => {
      if (json.paid) {
        //redirect
        playAudio();
        cart.payment_status = "paid";
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
  Quagga.stop();
  Swal.showLoading();
  axios({
    method: "post",
    url: "/api/process-payment/" + props.orderid,
    data: {
      products: cart.products,
      xcoin: document.getElementById("xcoin").value
    }
  }).then(function(response) {
    console.clear();
    cart.payment_status = "paying";
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
  open_camera();
  update_camera_viewer();
});
</script>
<template>
  <div class=" row  pos-header text-end">
    <div class="col-sm-12 col-12 col-md-3 col-lg-3 col-xl-3 pos-header_brand text-start">
      <div class=" row  p-1">
        <div class="col-4 col-sm-4 col-md-4 col-lg-5 col-xl-5"><img :src="'/storage/stores/' + props.logo" /></div>
        <div class="col-8 col-sm-8 col-md-8 col-lg-7 col-xl-7">
          <h1>{{ this_store.name }}</h1>
          <a href="/">[ Inicio ]</a>
          <div id="preview_camera" class="text-center"></div>
        </div>
      </div>
    </div>

    <div class="col-5 col-12  col-sm-12  col-lg-5 pos-header_filters_section">
      <div class=" row ">
        <div class="col-12">
          <span class="btn" v-if="show_camera" @click="update_camera_viewer()">Ocutar Camera</span>
          <span class="btn" v-else @click="update_camera_viewer()">Mostrar Camera</span>
        </div>

        <div class=" row  text-end">
            <table class="small text-start">
            <tr>
                <td>Codigo </td>
                <td>Nombre </td>
            </tr>
            <tr>
                <td><input
              class=""
              v-model="bar_code"
              placeholder="Digite el codigo o escaneelo"
              v-on:keyup="search_by_code"/>
              </td>
                <td>
                <input class="pos_product_name" placeholder="Digite el nombre" v-model="search_term" v-on:keyup="search_by_name" />
                </td>
            </tr>
            </table>


        </div>
      </div>
    </div>

    <div class="col-3 col-12  col-sm-12  col-lg-3 text-end pos-header_payment_section">
      <div class=" _row_  ">
        <div class="col-12 pos_payment_total">Total: $ {{ cart.total }}</div>
        <div class="col-12 pos_payment_action">
          Medio de Pago :
          <select id="xcoin" name="xcoin">
            <option value="ltc">Litecoin</option>
            <option value="doge">Dogecoin</option>
            <option value="bch">Bitcoin Cash</option>
            <option value="g-pay">Google Pay</option>
          </select>
          <br />
          <button v-if="cart.total != 0" class="btn mt-1 btn-primary center" v-on:click="show_payment_popup()">
            <i class="las la-2x la-wallet mx-2"></i>
            <h2 style="display:inline">Pagar</h2>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="  row  pos-main w-100 p-0 ">
    <div id="cart" class="col-10  col-sm-10 col-md-3 col-lg-4 col-xl-4 cart ">
      <div class="w-100">
        <div action="/process-payment" id="pay-form" method="post" class="border">
          <input type="hidden" name="_token" :value="csrf" /> <input type="hidden" name="stores_id" :value="this_store.id" />

          <ul v-if="cart.total == 0">
            <h3>Su Orden esta vacia, escane sus productos o agreguelos</h3>
          </ul>

          <ul class=" pos_cart_pos_list " v-else>
            <li class=" row  product_pos_cart_item cart_header">
              <div class="col-6 product_name">Nombre</div>
              <div class="col-2  product_price">Precio</div>
              <div class="col-2 product_qty">Qty</div>
              <div class="col-2">&nbsp;</div>
            </li>
            <li v-for="item in cart.products" v-bind:key="item.id" class=" row  product_pos_cart_item">
              <div class="col-6 product_name">{{ item.name }}</div>
              <div class="col-2 product_price">{{ item.price }}</div>
              <div class="col-2 product_qty">
                <input name="id[]" :value="item.id" type="hidden" readonly="readonly" />
                <input
                  name="qty[]"
                  :id="'cart_item_' + item.id"
                  :value="item.qty"
                  type="number"
                  v-on:keyup="update_cart_item(item.id)"
                  v-on:change="update_cart_item(item.id)"
                />
              </div>
              <div class="col-2">
                <span v-on:click="remove_from_cart(item.id)"> <i class="la  la-trash"></i></span>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class=" col-12  col-sm-12  col-md-8 col-lg-8 col-xl-8 scrollable h-100  p-0">
      <div v-if="show_search_panel">
        <products-list-component
          id="product_list"
          class="w-100 h-100 p-0 "
          :store_id="storeid"
          :products="this_store.products"
          @addToCart="addToCart"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import L from "leaflet";
import "@maptiler/leaflet-maptilersdk";
import ProductsListComponent from "../components/products-list.vue";


const map_key = "vWfLKhUw8HAs1XSRdInf";
const default_lat = 9.748916999999999;
const default_lng = -83.753428;



//      ^ What we're talking about
const props = defineProps({
  locales: Array,
});

const local_actual = reactive({
  is_defined: false,
  id: 0,
  link: 0,
  name: "",
  lat: 0,
  lng: 0,
  products: [],
});


let map;


onMounted(() => {
  function init_map() {
    const map_container = document.getElementById("map");

    if (!map_container) throw new Error('There is no div with the id: "MAP" ');

    map = L.map(map_container, {
      center: L.latLng(default_lat, default_lng),
      zoom: 8,
    });

    const mtLayer = new L.MaptilerLayer({
      apiKey: map_key,
    }).addTo(map);

    props.locales.forEach((local_item, index) => {
      var marker = L.marker([local_item.position_lat, local_item.position_lng])
        .addTo(map)
        .bindPopup("<b>" + local_item.name + "</b>")
        .closePopup();

      marker.on("click", function () {
        local_actual.id = local_item.id;
        local_actual.name = local_item.name;
        local_actual.lat = local_item.position_lat;
        local_actual.lng = local_item.position_lng;

        local_actual.link = "/store/" +  local_item.id;


        axios.get("/products/" + local_item.id).then((res) => {
          local_actual.products = res.data;
        });
      });
    });
  }

  init_map();

});
</script>
<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-5">
        <div id="map" class="map" ref="container"></div>

      </div>
      <div class="col-7">
        <div id="local_details" class="card">
          <div class="row">
            <div class="col-8">

                <a :href="local_actual.link" ><h1>{{ local_actual.name }}</h1></a>

              <div class="store-products">
                <products-list-component
                  :store_id="local_actual.id"
                  :products="local_actual.products"
                />
              </div>
            </div>
            <div class="col-4"><div id="preview_camera"></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

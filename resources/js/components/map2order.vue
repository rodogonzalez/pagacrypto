<script setup>
import { ref, reactive, onMounted } from 'vue'
import L from 'leaflet'
import '@maptiler/leaflet-maptilersdk'
import 'leaflet-routing-machine';

const map_key = 'vWfLKhUw8HAs1XSRdInf'


let default_lat = 10.016228
let default_lng = -84.204722


let default_lat_b = 9.997590
let default_lng_b = -84.112697


const props = defineProps({
    locales: Array,
    is_auth: false,
    pos: false
})



const local_actual = reactive({
    is_defined: false,
    id: 0,
    link: 0,
    name: '',
    lat: 0,
    lng: 0,
    description: '',
    phone: '',
    email: '',
    logo: '',
    products: []
})

let map

onMounted(() => {
    function init_map() {
        const map_container = document.getElementById('map')

        if (!map_container)
            throw new Error('There is no div with the id: "MAP" ')


        map = L.map(map_container, {
            center: L.latLng(default_lat, default_lng),
            zoom: 8
        })

        const mtLayer = new L.MaptilerLayer({
            apiKey: map_key
        }).addTo(map)

        let route_points = [
            L.latLng(default_lat, default_lng),
            L.latLng(default_lat_b, default_lng_b)
        ];

        L.Routing.control({
            waypoints: route_points,
            routeWhileDragging: true
        }).addTo(map);

    }
    init_map();
})
</script>
<template>
    <div class="container-fluid">
        <div class="row">

            <div class="col-7">

                <h1>{{ local_actual.name }}</h1>
                <div id="map" class="map" ref="container"></div>

            </div>

            <div class="col-5 border">








            </div>
        </div>

    </div>
</template>
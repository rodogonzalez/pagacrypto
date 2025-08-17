<script setup>
import BarChart from './charts/BarChart.vue'
import PieChart from './charts/PieChart.vue'
import LineChart from './charts/LineChart.vue'

import { ref, reactive, onMounted } from 'vue'
import L from 'leaflet'
import '@maptiler/leaflet-maptilersdk'

const map_key = 'vWfLKhUw8HAs1XSRdInf'
let default_lat = 9.748916999999999
let default_lng = -83.753428

const props = defineProps({
    locales: Array,
    is_auth: false,
    is_role: String,
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
});

let stats_data = reactive(
    {
        line_data: [],
        line_labels: [],
        pie_data: [],
        pie_labels: [],
        bar_data: [],
        bar_labels: [],

    }
);

stats_data.line_data = [1, 2, 3, 4, 5];
stats_data.line_labels = ['agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre'];
stats_data.pie_data = [30, 20, 40, 5];
stats_data.pie_labels = ['posible', 'imposible', 'necesario', 'contingente'];
stats_data.bar_data = [30, 20, 40, 5];
stats_data.bar_labels = ['posible', 'imposible', 'necesario', 'contingente'];



let map;

onMounted(() => {
    function init_map() {
        const map_container = document.getElementById('map')

        if (!map_container)
            throw new Error('There is no div with the id: "MAP" ')

        map = L.map(map_container, {
            //center: L.latLng(default_lat, default_lng),
            //zoom: 8
        })

        map.locate({ setView: true, maxZoom: 18 });


        const mtLayer = new L.MaptilerLayer({
            apiKey: map_key
        }).addTo(map)

        props.locales.forEach((local_item, index) => {
            var marker = L.marker([
                local_item.position_lat,
                local_item.position_lng
            ])
                .addTo(map)
                .bindPopup('<b><img class="logo_pic" src="/storage/stores/' + local_item.logo + '">' + local_item.name + '</b>')
                .closePopup()

            marker.on('click', function () {
                local_actual.is_defined = true;
                local_actual.id = local_item.id
                local_actual.name = local_item.name
                local_actual.email = local_item.email
                local_actual.phone = local_item.phone
                local_actual.logo = local_item.logo
                local_actual.description = local_item.description
                local_actual.lat = local_item.position_lat
                local_actual.lng = local_item.position_lng

            })
        })
    }
    init_map();
})
</script>
<template>
    <div class="container-fluid">
        <div class="row">

            <div class="col-7">

                <div id="map" class="map" ref="container"></div>

            </div>

            <div class="col-5 border">

                <div class="  card-panel" v-if="local_actual.is_defined">

                    <span><img :src="'/storage/stores/' + local_actual.logo" class="logo_pic2" />
                        <h1>{{ local_actual.name }}</h1>
                    </span>
                    <span>Whatsapp: <a :href="'https://wa.me/&text=hola&' + local_actual.phone + ''" target="_blank">{{
                        local_actual.phone }}</a></span><br>
                    <span>{{ local_actual.email }}</span><br>
                    <div class="  card-panel" v-if="props.is_auth">

                        <div class="row">
                            <div class="col-3">
                                <a :href="'/admin/pos/' + local_actual.id + '/'" class="btn">
                                    <i class="la la-shopping-cart"></i>&nbsp;Punto de Venta
                                </a>
                            </div>
                            <div class="col-3">

                                <a :href="'/admin/local/' + local_actual.id + '/edit'" class="btn">
                                    <i class="la la-cog"></i>&nbsp;Ajustes Generales
                                </a>

                            </div>
                            <div class="col-3">

                                <a :href="'/admin/product/?store_id=' + local_actual.id" class="btn">
                                    <i class="la la-warehouse"></i>&nbsp;Inventario
                                </a>

                            </div>
                        </div>
                    </div>

                    <span>{{ local_actual.description }}</span>
                </div>
                <div class="container  card-panel" v-else>


                    <div class="row">
                        <div class="col-6">
                            <BarChart :data="stats_data.bar_data" :labels="stats_data.bar_labels"  />
                        </div>
                        <div class="col-6">
                            <PieChart :data="stats_data.pie_data" :labels="stats_data.pie_labels"  />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <LineChart :data="stats_data.line_data" :labels="stats_data.line_labels"  />
                        </div>
                    </div>





                </div>

            </div>





        </div>
    </div>


</template>

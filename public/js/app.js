import './bootstrap';
import { createApp } from 'vue';
import { ref, reactive} from 'vue';
import L from "leaflet";
import "@maptiler/leaflet-maptilersdk";
import axios from "axios";

import '../static/sass/app.scss';
import HeaderComponent from './components/header.vue';
import MapComponent from './components/map.vue';
import PosComponent from './components/pos-dashboard.vue';

const app = createApp({});

let map, locales;
let local_name = ref('')
,    position_lng = ref(),
   position_lat = ref();


app.component('HeaderComponent', HeaderComponent);
app.component('MapComponent', MapComponent);
app.component('PosComponent', PosComponent);
app.mount('#app');


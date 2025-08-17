import './bootstrap';
import { createApp } from 'vue';
import { ref, reactive} from 'vue';


import "@maptiler/leaflet-maptilersdk";
import axios from "axios";

import '../static/sass/app.scss';


import HeaderComponent from './components/header.vue';
//import MapComponent from './components/map2order.vue';
import StoreComponent from './components/dashboard/Store.vue';
import StoresComponent from './components/dashboard/StoresList.vue';


import MapComponent from './components/dashboard/Guest.vue';
import PosComponent from './components/dashboard/POS.vue';
import AdminComponent from './components/dashboard/Admin.vue';
import OrderComponent from './components/order.vue';

import ChartBar from "./components/dashboard/ChartBar.vue";
import ChartPie from "./components/dashboard/ChartPie.vue";
import ChartLine from "./components/dashboard/ChartLine.vue";

import SoldComponent from "./components/dashboard/TopItemsSold.vue";
import CategoriesComponent from "./components/dashboard/TopCategoriesSold.vue";



//import cryptoLtcPayment from './components/crypto-ltc-payment.vue';

const app = createApp({});
/*
let map, locales;
let local_name = ref('')
,    position_lng = ref(),
   position_lat = ref();

*/
app.component('HeaderComponent', HeaderComponent);
app.component('MapComponent', MapComponent);
app.component('PosComponent', PosComponent);
app.component('OrderComponent', OrderComponent);
app.component('StoreComponent', StoreComponent);
app.component('AdminComponent', AdminComponent);

app.component('BarComponent', ChartBar);
app.component('PieComponent', ChartPie);
app.component('LineComponent', ChartLine);
app.component('StoresComponent', StoresComponent);

app.component('SoldComponent', SoldComponent);
app.component('CategoriesComponent', CategoriesComponent);




//app.component('PaymentComponent', cryptoLtcPayment);

app.mount('#app');


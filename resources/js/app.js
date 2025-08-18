import './bootstrap';
import { createApp } from 'vue';
import { ref, reactive} from 'vue';


import DepositComponent from "./components/order_deposit.vue"

import axios from "axios";

import '../static/sass/app.scss';


//import cryptoLtcPayment from './components/crypto-ltc-payment.vue';

const app = createApp({});

app.component('DepositComponent', DepositComponent);




//app.component('PaymentComponent', cryptoLtcPayment);

app.mount('#app');


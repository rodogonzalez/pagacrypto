<script setup>
import { ref, reactive, onMounted } from 'vue'
const props = defineProps(['storeid', 'custumer', 'order', 'products','expanded']);
const this_order = reactive({
    order: props.order,
    store_id: props.storeid,
    custumer: props.custumer,
    products: props.products,
});

let show_order = ref(props.expanded);


</script>
<template>
    <div v-if="!props.expanded">
        <a v-if="show_order" @click="show_order = !show_order;" class="btn">Ocultar Orden</a>
        <a v-else @click="show_order = !show_order;" class="btn">Ver Orden</a>

    </div>

    <div v-if="show_order">

        <table class="table cart  ">
            <thead  class="thead-dark">
                <tr  class="thead-dark ">
                    <td  class=""  >&nbsp;</td>
                    <td  class=""  align="left">Nombre</td>
                    <td   class=" ">Precio</td>
                    <td   class=" ">Qty</td>
                </tr>
            </thead>
            <tbody>
                <tr  scope="row"  v-for="item in props.products" v-bind:key="item.id" class="">
                    <td class=""><img :src="'/storage/products/' + item.image" class="order_product" /></td>

                    <td class="" align="left">{{ item.name }}</td>
                    <td class="">${{ item.price }}</td>
                    <td class="">{{ item.quantity }}</td>
                </tr>
            </tbody>
        </table>


    </div>


</template>

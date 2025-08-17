<script setup>
import { ref, reactive } from "vue";
const props = defineProps(["store_id", "products"]);
const emit = defineEmits(["addToCart"]);
</script>
<template>
  <div class="w-100 p-0 container ">
    <ul class=" row text-center w-100 p-0 text-end scrollable h-100 " >
      <table
        v-for="item in products"
        v-bind:key="item.id"
        class="card pos_product col-5 align-middle col-sm-5 col-md-4 col-lg-3 p-1 m-1 pos_list_item d-flex align-items-stretch "
        @click="$emit('addToCart', item)"
      >

        <tr class=" _row_ ">
            <td class='col-4 center align-middle'><img :src="'/storage/products/' + item.image" class="pos_product_image h-100" /></td>
            <td class="col-8">
                <h5 class="card-title">{{ item.name }}</h5>
                <p class="card-text">${{ item.price }}</p>
                <p class="card-text">
                    <small class="text-muted">{{ item.barcode }}</small>
                </p>
            </td>
        </tr>



      </table>
    </ul>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
const props = defineProps(["store_id", "products"]);
const emit = defineEmits(["addToCart"]);

</script>
<template>
  <h4>Productos</h4>
  <div class="container">
    <ul class="product_pos_list">
      <li
        v-for="item in products"
        v-bind:key="item.id"
        class="product_pos_list_item"
        @click="$emit('addToCart', item)"
      >
        <div class="row">
          <div class="col-4 product_pos_list_item_picture">
            <img :src="'/storage/products/' + item.image" class="" />
          </div>
          <div class="col-8 product_pos_list_item_description">
            <div>{{ item.name }}</div>
            <div>{{ item.price }}</div>
            <div>{{ item.barcode }}</div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

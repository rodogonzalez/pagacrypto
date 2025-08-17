<script setup>
const props = defineProps({
  items: Object,
  currency_symbol: String
});
</script>
<template>
  <div class="row">
    <table class="table table-bordered table-striped w-100">
      <thead>
        <tr>
          <th>Articulo</th>
          <th>Cantidad</th>
          <th>inStock</th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="item in props.items" v-bind:key="item.id" class="small">

          <td><a :href="'/admin/product/' + item.id + '/edit'"  > {{ item.name }} ({{ item.store_name }})</a></td>
          <td>{{ item.total }}</td>
          <td>{{ item.in_stock }}</td>


        </tr>
      </tbody>
    </table>
  </div>
</template>

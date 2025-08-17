<script setup>
const props = defineProps({
  items: Object,
  currency_symbol: String
});
</script>
<template>
  <div class="row">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th >Articulo</th>
          <th>Cantidad</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in props.items" v-bind:key="item.id">

          <td>{{ item.name }}</td>
          <td>{{ item.total }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

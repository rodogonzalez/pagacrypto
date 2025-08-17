<script setup>
const props = defineProps({
    stores: Object,
});
</script>
<template>
    <div class="row">
        <div class="col-12  p-1   ">
            <div class="card ">
                <div class="row m-1">
                    <a href="/admin/local/create" class="btn"><h4><i class="las la-3x la-plus-circle"></i> Crear Tienda</h4></a>

                </div>
            </div>
        </div>
    </div>

    <div class="row  scrollable stores_list">


        <div v-for="item in stores" v-bind:key="item.id" class="col-12 p-1">
            <div class="card">
                <div class="row m-1">
                    <div class="col-12">
                        <div class="row mt-2">
                            <div class="col-5">
                               <a :href="'/admin/local/' +item.id + '/edit'"> <img :src="'/storage/stores/' + item.logo" />
                                </a>
                            </div>

                            <div class="col-7">

                                <h3>{{ item.name }}</h3><h4>{{ item.category_name }}</h4>


                                <a :href="'/admin/pos/' + item.id" class="small"
                                    ><i class="lar la-1x la-window-restore"></i
                                    >Punto de Venta</a
                                ><br />
                                <a
                                    :href="
                                        '/admin/product/?store_id=' + item.id
                                    "
                                    class="small"
                                    ><i class="las la-1x la-warehouse"></i>
                                    {{ item.total_products }} Inventario </a
                                ><br />

                                <a
                                    :href="'/admin/order/?stores_id=' + item.id"
                                    class="small"
                                    ><i class="las la-1x la-file-invoice"></i>
                                    {{ item.total_orders }} Ordenes</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>








    </div>
</template>

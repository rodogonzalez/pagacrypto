<script setup>

import BarChart from "./charts/BarChart.vue";
import PieChart from "./charts/PieChart.vue";
import LineChart from "./charts/LineChart.vue";
import { Tabs, Tab } from "vue3-tabs-component";
import { ref, reactive, onMounted } from "vue";

const props = defineProps({
    is_auth: false,
    user_id: Number,
    is_role: String,
    category_stats: Object,
    bar_stats: Object,
    sold_stats: Object,
    pos: false,
    today_sold_items: Number,
    total_sold_items: Number,
    new_products: Number
});

const stats_data = reactive({
    line_data: [],
    line_labels: [],
    pie_data: [],
    pie_labels: []

});

stats_data.line_data = props.sold_stats.totals;
stats_data.line_labels = props.sold_stats.labels;
stats_data.pie_data = props.category_stats.totals;
stats_data.pie_labels = props.category_stats.labels;

</script>
<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div>
                    <tabs
                        :options="{ useUrlFragment: false }"
                        @clicked="tabClicked"
                        @changed="tabChanged"
                        nav-item-class="nav-item"
                    >
                        <tab name="Ventas">
                            <div class="row">
                                <div class="col-5">
                                    <LineChart
                                        :data="stats_data.line_data"
                                        :labels="stats_data.line_labels"
                                    />
                                </div>
                                <div class="col-7">
                                    <div class="card">
                                        <div class="row">
                                            <div class="col-3">
                                                {{ props.today_sold_items}}
                                            </div>
                                            <div class="col-9">
                                                Ventas de Hoy
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="row">
                                            <div class="col-3">{{ props.total_sold_items }}</div>
                                            <div class="col-9">
                                                Total de Ventas
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="row">
                                            <div class="col-3">{{ props.new_products}}</div>
                                            <div class="col-9">
                                                Productos Nuevos
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tab>
                        <tab name="Inventario">
                            <div class="row">
                                <div class="col-4">
                                    <PieChart
                                        :data="stats_data.pie_data"
                                        :labels="stats_data.pie_labels"
                                    />
                                </div>
                                <div class="col-8">
                                    <BarChart
                                        :data="stats_data.pie_data"
                                        :labels="stats_data.pie_labels"
                                    />
                                </div>
                            </div>
                        </tab>
                        <tab name="Contabilidad">
                            <div class="col-6">
                                poner aqui retiros, saldos disponibles,
                                devoluciones
                            </div>
                        </tab>
                    </tabs>
                </div>
            </div>
        </div>
    </div>
</template>

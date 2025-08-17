<script setup>
import PieChart from "./charts/PieChart.vue";

import {reactive } from "vue";

const props = defineProps({
    stats: Object
});

const stats_data = reactive({
    data: [],
    labels: []
});

stats_data.data = props.stats.totals;
stats_data.labels = props.stats.labels;

</script>
<template>

    <PieChart
        :data="stats_data.data"
        :labels="stats_data.labels"     />
</template>

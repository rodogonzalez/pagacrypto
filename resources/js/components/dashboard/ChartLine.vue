<script setup>

import LineChart from "./charts/LineChart.vue";

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

    <LineChart
        :data="stats_data.data"
        :labels="stats_data.labels"     />
</template>

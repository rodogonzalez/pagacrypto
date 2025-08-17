<script setup>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

const props = defineProps(['data', 'labels']);

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const barData = {
    labels: props.labels,

    datasets: [{
        backgroundColor: ['#445257', '#405961', '#355c69', '#545c5e', '#064e66',
        '#1f637a', '#738287', '#617c85', '#03475e', '#0f3d4d'],
        data: props.data
    }]
};

const lineOptions = {
    responsive: true,
    plugins: {
        legend: {
            display: false
        }
    }
}

</script>

<template>
    <Bar id="my-chart-id" :options="lineOptions" :data="barData" />
</template>

<script setup>
const props = defineProps(["data", "labels"]);

import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from "chart.js";
import { Line } from "vue-chartjs";

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

const lineData = {
  labels: props.labels,
  datasets: [
    {
      label: "Ordenes Completas",
      backgroundColor: [
        "#445257",
        "#405961",
        "#355c69",
        "#545c5e",
        "#064e66",
        "#1f637a",
        "#738287",
        "#617c85",
        "#03475e",
        "#0f3d4d"
      ],
      data: props.data
    }
  ]
};

const lineOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    }
  }
};
</script>

<template>
  <Line :data="lineData" :options="lineOptions" />
</template>

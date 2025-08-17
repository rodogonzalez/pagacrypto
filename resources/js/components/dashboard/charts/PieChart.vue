<script setup>
const props = defineProps(["data", "labels"]);
import { Pie } from "vue-chartjs";

import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";

ChartJS.register(ArcElement, Tooltip, Legend);

const pieData = {
  labels: props.labels,

  datasets: [
    {
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

const pieOptions = {
  responsive: true,
  plugins: {
    legend: {
      display: false
    }
  }
};
</script>
<template>
  <Pie :data="pieData" :options="pieOptions" />
</template>

<template>
  <canvas ref="canvas"></canvas>
</template>

<script>
import { Pie } from "vue-chartjs";

/**
 *  ГРАФИК КРУГА (пончик)
 *
 * Принимает объект со свойствами @chartdata, @options
 * @chartdata - объект со свойствами:
 *  label array
 *  dataset array
 *
 * */
export default {
  extends: Pie,
  props: {
    initialData: {
      type: Object,
      default: null
    }
  },
  mounted() {
    let {
      chartdata,
      options = {
        scales: {
          angleLines: {
            display: false
          }
        },
        legend: { display: true }
      }
    } = this.initialData;

    chartdata.datasets[0].backgroundColor = [
      "rgba(255, 99, 132, 0.8)",
      "rgba(54, 162, 235, 0.8)",
      "rgba(255, 206, 86, 0.8)",
      "rgba(75, 192, 192, 0.8)",
      "rgba(153, 102, 255, 0.8)",
      "rgba(255, 159, 64, 0.8)",
      "rgba(70, 129, 64, 0.8)",
      "rgba(170, 119, 64, 0.8)",
      "rgba(240, 189, 64, 0.8)",
      "rgba(10, 70, 120, 0.8)"
    ];

    this.renderChart(chartdata, options);
  }
};
</script>

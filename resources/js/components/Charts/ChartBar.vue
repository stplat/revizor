<template>
    <canvas ref="canvas"></canvas>
</template>

<script>
  import { Bar } from 'vue-chartjs';

  /**
   *  ГРАФИК ГИСТОГРАММЫ
   *
   * Принимает объект со свойствами @chartdata, @options
   * @chartdata - объект со свойствами:
   *  label array
   *  dataset array
   *
   * */
  export default {
    extends: Bar,
    props: {
      initialData: {
        type: Object,
        default: null
      }
    },
    mounted() {
      let {
        chartdata, options = {
          scales: { yAxes: [ { ticks: { beginAtZero: true } } ] },
          legend: { display: false }
        }
      } = this.initialData;

      this.renderChart(chartdata, options);
    }
  };
</script>

<template>
  <canvas ref="canvas"></canvas>
</template>

<script>
import { Line } from "vue-chartjs";

/**
 *  График линии
 *
 * */
export default {
  extends: Line,
  props: {
    initialData: {
      type: Object,
      default: null
    }
  },
  mounted() {
    let {
      chartdata,
      options {
        scales: {
          y: {
            stacked: true
          }
        }
      }
    } = this.initialData;

    this.renderChart(chartdata, options);
  }
};
</script>

<template>
  <div id="container" style="width:100%"></div>
</template>

<script>
var Highcharts = require("highcharts");
// Load module after Highcharts is loaded
require("highcharts/modules/exporting")(Highcharts);

/**
 *  ГРАФИК ГОРИЗОНТАЛЬНОГО БАРА
 *
 *
 * */
export default {
  props: {
    data: {
      type: Array,
      required: true
    }
  },
  mounted() {
    const data = this.data;

    const chart = Highcharts.chart("container", {
      chart: {
        type: "bar"
      },
      title: false,
      xAxis: {
        lineWidth: 0,
        minorGridLineWidth: 0,
        lineColor: "transparent",
        labels: {
          enabled: false
        },
        minorTickLength: 0,
        tickLength: 0
      },
      yAxis: {
        min: 8,
        max: 20,
        tickInterval: 1,
        title: {
          text: "Часы"
        }
      },
      legend: false,
      plotOptions: {
        series: {
          stacking: "normal"
        }
      },
      series: data,
      tooltip: {
        formatter: function(v) {
          return data[this.series.index].tooltipData;
        }
      }
    });
  }
};
</script>

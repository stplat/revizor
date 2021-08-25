<template>
    <chart-wheel :initial-data="computedWheelChart"></chart-wheel>
</template>
<script>
  export default {
    computed: {
      computedWheelChart() {
        const violations = this.$store.getters['ViewCheck/getViolationsGroupByName'];

        const chartdata = {
          labels: violations.map(item => item.name),
          datasets: [ {
            data: violations.map(item => item.count)
          } ]
        };

        if (!violations.length) {
          this.$emit('empty');
        }

        const options = {
          scales: {
            yAxes: [ {
              ticks: { beginAtZero: true, stepSize: 1 },
              display: false,
              scaleLabel: {
                display: true,
                labelString: ''
              }
            } ],
            xAxes: [ {
              display: false,
              scaleLabel: {
                display: true,
                labelString: ''
              }
            } ]
          },
          legend: { display: true }
        };

        return { chartdata, options };
      }
    }
  }
</script>

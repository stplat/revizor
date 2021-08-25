<template>
  <chart-wheel :initial-data="computedWheelChart"></chart-wheel>
</template>
<script>
  export default {
    computed: {
      computedWheelChart() {
        const check = this.$store.getters['ViewCheck/getCheck'];
        const data = [],
          labels = [];

        if (check.videos_processing_select_false_count) {
          data.push(check.videos_processing_select_false_count);
          labels.push('Выбор камеры');
        }

        if (check.videos_processing_box_false) {
          data.push(check.videos_processing_box_false);
          labels.push('Поиск ящиков');
        }

        if (check.videos_processing_counting_false_count) {
          data.push(check.videos_processing_counting_false_count);
          labels.push('Подсчёт явки');
        }

        if (check.real_counting_videos_count) {
          data.push(check.real_counting_videos_count);
          labels.push('Ручной подсчёт явки');
        }

        if (check.checking_videos_count) {
          data.push(check.checking_videos_count);
          labels.push('Проверка работы Ревизора');
        }

        if (check.videos_to_select_cam_count) {
          data.push(check.videos_to_select_cam_count);
          labels.push('В очереди на выбор камеры');
        }

        if (check.videos_to_find_boxes_count) {
          data.push(check.videos_to_find_boxes_count);
          labels.push('В очереди на поиск ящиков');
        }

        if (check.videos_to_count_count) {
          data.push(check.videos_to_count_count);
          labels.push('В очереди на подсчет явки');
        }

        if (check.videos_to_real_count_count) {
          data.push(check.videos_to_real_count_count);
          labels.push('В очереди на ручной подсчет явки');
        }

        if (check.videos_to_check_count) {
          data.push(check.videos_to_check_count);
          labels.push('В очереди на проверке Ревизора');
        }

        if (!data.length) {
          this.$emit('empty');
        }

        const chartdata = {
          labels,
          datasets: [ {
            data
          } ]
        };

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

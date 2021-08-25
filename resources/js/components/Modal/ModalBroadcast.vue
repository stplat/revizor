<template>
  <div>
    <div class="card card--small">
      <div class="card__header">
        <h3>Наличие трансляции видео. Камера #{{ computedCamera }}</h3>
      </div>
      <div class="card__body">
        <div class="pill" v-for="(item, key) in computedDays" :key="key">
          <input
            type="radio"
            :id="'pill-' + key"
            hidden
            :value="item"
            v-model="day"
          />
          <label :for="'pill-' + key">{{ item }} </label>
        </div>
        <chart-high-bar :data="computedChartBar" :key="day"></chart-high-bar>
      </div>
    </div>
    <div class="card card--small">
      <preloader v-if="isLoading"></preloader>
      <div class="card__header">
        <h3>Периоды, когда не было трансляции</h3>
      </div>
      <div class="card__body">
        <div class="event__toggle" data-key="Больше минуты:">
          <input-toggle-radio
            value="moreMinute"
            v-model="data.duration"
            @input="changeData"
          ></input-toggle-radio>
        </div>
        <div class="event__toggle" data-key="Больше часа:">
          <input-toggle-radio
            value="moreHour"
            v-model="data.duration"
            @input="changeData"
          ></input-toggle-radio>
        </div>
        <div class="event__toggle" data-key="Нет трансляции:">
          <input-toggle-radio
            value="none"
            v-model="data.duration"
            @input="changeData"
          ></input-toggle-radio>
        </div>
        <v-client-table
          :data="table.data"
          :columns="table.columns"
          :options="table.options"
        >
        </v-client-table>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    camera: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      data: {
        duration: ""
      },
      isLoading: false,
      day: ""
    };
  },
  computed: {
    computedChartBar() {
      return this.$store.getters["Modal/getVideoTimes"]
        .reduce(
          (carry, item) => (item.day === this.day ? item.timings : carry),
          []
        )
        .reduce((carry, item) => {
          const count = carry.length;

          carry.push({
            durationInHours: item.durationInHours,
            data: count
              ? [item.durationInHours - carry[count - 1].durationInHours]
              : [item.durationInHours],
            color: item.broadcast ? "green" : "red",
            tooltipData: `${item.timeStart} - ${item.timeEnd}`
          });

          return carry;
        }, [])
        .reverse();
    },
    table() {
      const headings = {
        key: "№",
        date: "Дата",
        disappeared: "Пропала",
        appeared: "Появилась",
        duration: "Длительность"
      };

      const data = this.$store.getters["Modal/getViolationList"].map(
        (item, i) => ({
          key: i + 1,
          date: item.date,
          disappeared: this.formatTimeHelper(item.disappeared),
          appeared: this.formatTimeHelper(item.appeared),
          duration: item.duration
        })
      );

      return {
        data,
        options: {
          headings,
          _data: data,
          sortByColumn: true,
          sortable: ["key", "date"]
        },
        columns: Object.keys(headings)
      };
    },
    /**
     * Определеяем камеру
     * @return boolean
     */
    computedCamera() {
      const cameras = this.$store.getters["Modal/getUik"].cameras;

      return cameras.reduce((carry, item) => {
        if (String(item.id) === this.camera) {
          return item.number;
        }

        return carry;
      }, "");
    },
    /**
     * Кнопки с днями
     * @return boolean
     */
    computedDays() {
      const days = this.$store.getters["Modal/getVideoTimes"].map(
        item => item.day
      );

      this.day = days.length ? days[0] : "";
      return days;
    }
  },
  watch: {},
  methods: {
    changeData(e) {
      this.isLoading = true;
      let { duration } = this.data;

      this.$store
        .dispatch("Modal/showViolationList", { duration, camera: this.camera })
        .then(res => {
          if (!res.hasOwnProperty("errors")) {
          } else {
            this.errors = res.errors;
          }
          this.isLoading = false;
        });
    }
  },
  mounted() {}
};
</script>
<style lang="scss">
.highcharts-figure,
.highcharts-data-table table {
  min-width: 310px;
  max-width: 800px;
  margin: 1em auto;
}

#container {
  height: 200px;
}

.highcharts-data-table table {
  font-family: Verdana, sans-serif;
  border-collapse: collapse;
  border: 1px solid #ebebeb;
  margin: 10px auto;
  text-align: center;
  width: 100%;
  max-width: 500px;
}
.highcharts-data-table caption {
  // padding: 1em 0;
  font-size: 1.2em;
  color: #555;
}
.highcharts-data-table th {
  font-weight: 600;
  // padding: 0.5em;
}
.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
  // padding: 0.5em;
}
.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}
.highcharts-data-table tr:hover {
  background: #f1f7ff;
}
</style>

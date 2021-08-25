<template>
  <div class="event">
    <preloader v-if="isLoading"></preloader>
    <div class="event__tile">
      <ul class="event-tile">
        <li
          class="event-tile__item"
          :class="item.class"
          v-for="(item, key) in computedTile"
          :key="key"
          v-if="item.value"
        >
          <div class="event-tile__title">{{ item.title }}</div>
          <div class="event-tile__value">{{ item.value }}</div>
        </li>
      </ul>
    </div>
    <div class="event__container mt-4">
      <div class="event__left-col">
        <div class="event__title">
          Список событий
          <div class="event__tip">
            <tip button-type="question" title="Подсказка"></tip>
          </div>
        </div>
        <div class="event__toggle" data-key="Новое событие:">
          <input-toggle
            v-model="data.event"
            @change="changeData"
          ></input-toggle>
        </div>
        <div class="event__toggle" data-key="Сформирован жалоба:">
          <input-toggle
            v-model="data.petition"
            @change="changeData"
          ></input-toggle>
        </div>
        <div class="event__toggle" data-key="Пришёл ответ:">
          <input-toggle
            v-model="data.answer"
            @change="changeData"
          ></input-toggle>
        </div>
        <div class="event__table mt-4">
          <event-table></event-table>
        </div>
      </div>
      <div class="event__right-col" v-if="eventChart">
        <div class="uik__title mb-2">Распределение событий</div>
        <div class="event__chart">
          <event-chart @empty="eventChart = false"></event-chart>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {
    EventTable: require("./EventTable.vue").default,
    EventChart: require("./EventChart.vue").default
  },
  data() {
    return {
      data: {
        check_id: null,
        event: false,
        petition: false,
        answer: false
      },
      eventChart: true,
      isLoading: false
    };
  },
  methods: {
    changeData() {
      this.isLoading = true;

      let { check_id, event, petition, answer } = this.data;

      const statuses = [];

      if (event) statuses.push(1);
      if (petition) statuses.push(3);
      if (answer) statuses.push(5);

      this.$store
        .dispatch("ViewCheck/showViolations", { check: check_id, statuses })
        .then(res => {
          if (!res.hasOwnProperty("errors")) {
          } else {
            this.errors = res.errors;
          }

          this.isLoading = false;
        });
    }
  },
  computed: {
    computedTile() {
      const check = this.$store.getters["ViewCheck/getCheck"];

      return [
        {
          title: "Сбой трансляции",
          value: check.violation_type_id_1_datetime_null_count,
          class: ""
        },
        {
          title: "Не хватает видео",
          value: check.violation_type_id_1_datetime_not_null_count,
          class: "event-tile__item--video"
        },
        {
          title: "Расположение ящиков",
          value: check.violation_type_id_4_5_count,
          class: "event-tile__item--area"
        },
        {
          title: "Отклонение явки",
          value: check.violation_type_id_6_7_count,
          class: "event-tile__item--offset"
        }
      ];
    }
  },
  mounted() {
    this.data.check_id = this.$store.getters["ViewCheck/getCheck"].check_id;
  }
};
</script>

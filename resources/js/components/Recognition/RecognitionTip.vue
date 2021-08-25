<template>
  <tip :title="computedUik.name">
    <template v-slot:btn>{{ computedUik.name }}</template>
    <div class="tip-dropdown__title mb-3">Об участке</div>
    <ul class="tip-dropdown__list">
      <li data-key="Идентификатор:">{{ computedUik.uik_id }}</li>
      <li data-key="Адресс:">{{ computedUik.address }}</li>
      <li data-key="Подходид для подсчёта:">
        {{ computedUik.countable | boolean }}
      </li>
      <li data-key="Время на участке:" class="space-no-wrap">
        {{ computedDate }}
      </li>
    </ul>
    <button class="btn btn--light mt-2">Подробнее</button>
    <template v-for="(cam, key) in computedCameras">
      <div class="tip-dropdown__title mb-3 mt-4" :key="key">
        Камера #{{ cam.number }}
      </div>
      <ul class="tip-dropdown__list">
        <li data-key="Выбрана для подсчёта явки:">
          {{ cam.countable | boolean }}
        </li>
        <li data-key="Идентификатор:">{{ cam.code }}</li>
      </ul>
    </template>
  </tip>
</template>
<script>
export default {
  props: {
    uik: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      date: null
    };
  },
  created() {
    const date = this.computedUik.date;

    this.date = new Date(date).getTime();
    setInterval(this.timeCounter, 1000);
  },
  methods: {
    /**
     * Счетчик времени для посдсказки
     * @return void
     */
    timeCounter() {
      if (this.date) {
        this.date += 1000;
      }
    }
  },
  computed: {
    /**
     * Данные по участку
     * @return object
     */
    computedUik() {
      return this.uik;
    },
    /**
     * Данные по камерам участка для подсказки
     * @return array
     */
    computedCameras() {
      return this.computedUik.cameras;
    },
    /**
     * Дата и время участка с секундомером для подсказки
     * @return string
     */
    computedDate() {
      if (!this.date) return "";

      const date = new Date(this.date);

      const year = date.getFullYear();
      const month =
        date.getMonth() < 9
          ? "0" + Number(date.getMonth() + 1)
          : Number(date.getDate() + 1);
      const day = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();

      const hours =
        date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
      const minutes =
        date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
      const seconds =
        date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();

      return `${hours}:${minutes}:${seconds} ${year}.${month}.${day}`;
    }
  },
  filters: {
    boolean(value) {
      return value ? "да" : "нет";
    }
  }
};
</script>

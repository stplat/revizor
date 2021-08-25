<template>
  <tip :title="computedName">
    <template v-slot:btn>{{ computedName }}</template>
    <div class="tip-dropdown__title mb-3">Об участке</div>
    <ul class="tip-dropdown__list">
      <li data-key="Идентификатор:">{{ computedUik.id }}</li>
      <li data-key="Адрес:">{{ computedUik.address }}</li>
      <li data-key="Подходит для подсчета:">
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
    /* Текущая камера */
    camera: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      date: null,
      errors: []
    };
  },
  created() {
    const date = this.computedUik.date;

    this.date = new Date(date).getTime();
    setInterval(this.timeCounter, 1000);
  },
  methods: {
    timeCounter() {
      if (this.date) {
        this.date += 1000;
      }
    }
  },
  computed: {
    computedViolationType() {
      return this.$store.getters["Modal/getViolation"].type;
    },
    computedUik() {
      return this.$store.getters["Modal/getUik"];
    },
    computedCameras() {
      const cameras = this.$store.getters["Modal/getUik"].cameras;

      return this.computedViolationType === 1
        ? cameras.filter(item => String(item.id) === this.camera)
        : cameras;
    },
    computedName() {
      const uik = this.computedUik;
      const type = this.computedViolationType;
      const camera = uik.cameras.reduce((carry, item) => {
        return String(item.id) === this.camera ? item.number : carry;
      }, "");

      return type === 1 ? uik.name + ", камера " + camera : uik.name;
    },
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

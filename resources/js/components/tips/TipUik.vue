<template>
  <tip :title="computedName" :isLoading="isLoading">
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
    <template v-for="(cam, key) in computedUik.cameras">
      <template v-if="violationType !== '1' || String(cam.id) === camera">
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
    </template>
  </tip>
</template>
<script>
import tip from "./tip.js";

export default {
  mixins: [tip],
  props: {
    uik: {
      type: String,
      required: true
    },
    camera: {
      type: String,
      required: false
    },
    violationType: {
      type: String,
      required: false
    }
  },
  data() {
    return {
      date: null,
      isLoading: true,
      errors: []
    };
  },
  mounted() {
    this.$store.dispatch("Tip/showUik", { uik: this.uik }).then(res => {
      if (res.hasOwnProperty("errors")) {
        this.errors = res.errors;
      } else {
        this.errors = [];
        this.date = new Date(res.date).getTime();
        setInterval(this.timeCounter, 1000);
      }
      this.isLoading = false;
    });
  },
  methods: {
    timeCounter() {
      if (this.date) {
        this.date += 1000;
      }
    }
  },
  computed: {
    computedName() {
      const uik = this.$store.getters["Tip/getUik"];

      if (this.violationType === "1" && uik.hasOwnProperty("cameras")) {
        const camera = uik.cameras.reduce((carry, item) => {
          return String(item.id) === this.camera ? item.number : carry;
        }, "");

        return uik.hasOwnProperty("name")
          ? uik.name + ", камера " + camera
          : "";
      }

      return uik.hasOwnProperty("name") ? uik.name : "";
    },
    computedUik() {
      return this.$store.getters["Tip/getUik"];
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

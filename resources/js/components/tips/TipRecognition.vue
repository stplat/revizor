<template>
  <tip :title="title" button-type="info" :isLoading="isLoading">
    <div class="tip-dropdown__title mb-3">Основное</div>
    <ul class="tip-dropdown__list">
      <li data-key="Идентификатор:">{{ recognition.id }}</li>
      <li data-key="Время фото:">{{ recognition.imageDate }}</li>
      <li data-key="Количество ящиков:">{{ recognition.boxCount }}</li>
      <li data-key="Тип ящиков:">
        {{ recognition.boxTypes | boxTypeDecryptor }}
      </li>
      <li data-key="Минимальный коэф. размера ящиков:">
        {{ parseInt(recognition.coefficient * 100) }}%
      </li>
    </ul>
    <div class="tip-dropdown__title mb-3 mt-5">О проверке распознавания</div>
    <ul class="tip-dropdown__list">
      <li data-key="Проверено:">{{ recognition.checked | boolean }}</li>
      <li data-key="Проверил:">{{ recognition.username }}</li>
      <li data-key="Время проверки:">{{ recognition.checkDate }}</li>
    </ul>
  </tip>
</template>
<script>
import tip from "./tip.js";

export default {
  mixins: [tip],
  props: {
    image: {
      type: Number,
      required: false,
      default: 1
    }
  },
  data() {
    return {
      recognition: {
        id: null,
        imageDate: "",
        boxCount: "",
        boxTypes: [],
        checked: false,
        username: "",
        checkTime: "",
        coefficient: "39% (меньше 40%)"
      },
      errors: [],
      isLoading: true
    };
  },
  mounted() {
    this.$store
      .dispatch("Tip/showRecognition", { image: this.image })
      .then(res => {
        if (res.hasOwnProperty("errors")) {
          this.errors = res.errors;
        } else {
          this.errors = [];
          this.recognition = Object.assign({}, res);
          this.isLoading = false;
        }
      });
  },
  filters: {
    boxTypeDecryptor(value) {
      return value
        .map(item => (item === "koib" ? "КОИБ" : "Избирательный ящик"))
        .join(" и");
    },
    boolean(value) {
      return value ? "да" : "нет";
    }
  }
};
</script>

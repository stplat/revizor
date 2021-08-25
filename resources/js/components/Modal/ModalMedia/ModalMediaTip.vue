<template>
  <tip title="Информация о распозновании" button-type="info">
    <div class="tip-dropdown__title mb-3">Основное</div>
    <ul class="tip-dropdown__list">
      <li data-key="Идентификатор:">{{ image.id }}</li>
      <li data-key="Время фото:">{{ image.date }}</li>
      <li data-key="Количество ящиков:">{{ image.boxCount }}</li>
      <li data-key="Тип ящиков:">
        {{ image.boxTypes | boxTypeDecryptor }}
      </li>
      <li data-key="Минимальный коэф. размера ящиков:">
        {{ parseInt(image.coefficient * 100) }}%
      </li>
    </ul>
    <div class="tip-dropdown__title mb-3 mt-5">О проверке распознавания</div>
    <ul class="tip-dropdown__list">
      <li data-key="Проверено:">{{ image.checked | boolean }}</li>
      <li data-key="Проверил:">{{ image.username }}</li>
      <li data-key="Время проверки:">{{ image.checkDate }}</li>
    </ul>
  </tip>
</template>
<script>
export default {
  props: {
    image: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      errors: []
    };
  },
  mounted() {},
  filters: {
    boxTypeDecryptor(value) {
      if (!value) return "";
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

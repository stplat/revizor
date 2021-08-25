<template>
  <popup @close="$emit('close')" width="740px" :shown="shown">
    <template v-slot:body>
      <div class="card__title"><h3>Камера</h3></div>
      <ul class="list">
        <li
          class="list__item list__item--key"
          data-key="Подходит для подсчета:"
        >
          <s-radio-toggle
            v-model="computedCountableCamera"
            :value="String(camera.id)"
            :disabled="true"
          ></s-radio-toggle>
        </li>
        <li class="list__item list__item--key" data-key="Тип ящиков:">
          {{ camera.boxTypes | boxType }}
        </li>
        <li class="list__item list__item--key" data-key="Количество ящиков:">
          {{ camera.image.boxes.length }}
        </li>
      </ul>
      <image-editor
        :backgroundImagePath="camera.image.raw_image"
        v-model="computedBoxList"
        :disabled="true"
      ></image-editor>
    </template>
  </popup>
</template>
<script>
export default {
  model: {
    prop: "modelValue",
    event: "update:modelValue"
  },
  props: {
    shown: {
      type: Boolean,
      required: true
    },
    camera: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      boxList: []
    };
  },
  computed: {
    /**
     * Переключатель "Подходит для подсчета" у камеры
     * @return boolean
     */
    computedCountableCamera() {
      return this.camera.countable;
    },
    /**
     * Ящики
     * @return array
     */
    computedBoxList() {
      return this.camera.image.boxes;
    }
  },
  filters: {
    boxType(value) {
      return value
        .map(item => (item === "ballot_box" ? "Избирательный ящик" : "КОИБ"))
        .join(" и ");
    }
  }
};
</script>

<template>
  <ul class="input-checkbox-multi">
    <li v-for="(item, key) in data" :key="'multi-radio-' + key">
      <s-checkbox
        v-model="computedModelValue"
        :value="item.value"
        :name="item.hasOwnProperty('name') ? item.name : computedName"
        :tag="item.tag"
        :disabled="item.disabled"
        >{{ item.label }}</s-checkbox
      >
    </li>
  </ul>
</template>
<script>
export default {
  model: {
    prop: "modelValue",
    event: "update:modelValue"
  },
  props: {
    modelValue: {
      type: Array,
      required: true
    },
    data: {
      type: Array,
      required: true,
      validator: array => {
        return array.every(
          item => item.hasOwnProperty("value") && item.hasOwnProperty("label")
        );
      }
    }
  },
  computed: {
    /**
     * Уникальное имя для группы radio-кнопок
     * @return string
     */
    computedName() {
      return "multi-checkbox-" + this._uid;
    },
    /**
     * Геттер и сеттер для модели
     * @return string
     */
    computedModelValue: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit("update:modelValue", value);
      }
    }
  }
};
</script>

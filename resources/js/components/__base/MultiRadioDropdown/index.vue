<template>
  <btn-dropdown :placeHolder="computedCheckedLabel">
    <m-radio v-model="computedModelValue" :data="data"></m-radio>
  </btn-dropdown>
</template>
<script>
export default {
  model: {
    prop: "modelValue",
    event: "update:modelValue"
  },
  props: {
    modelValue: {
      type: String,
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
     * Название метки у выбранной radio-кнопки
     * @return string
     */
    computedCheckedLabel() {
      return this.data.reduce((carry, item) => {
        return item.value === this.modelValue ? item.label : carry;
      }, "");
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

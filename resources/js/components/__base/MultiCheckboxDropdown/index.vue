<template>
  <search-dropdown v-model="search">
    <m-checkbox
      v-if="type === 'default'"
      v-model="computedModelValue"
      :data="computedData"
    ></m-checkbox>
    <m-checkbox-xls
      v-if="type === 'xls'"
      v-model="computedModelValue"
      :data="computedData"
    ></m-checkbox-xls>
  </search-dropdown>
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
    },
    type: {
      type: String,
      required: false,
      default: "default",
      validator: type => {
        return ["default", "xls"].includes(type);
      }
    }
  },
  data() {
    return {
      search: ""
    };
  },
  computed: {
    /**
     * Название метки у выбранной radio-кнопки
     * @return string
     */
    computedType() {
      return this.data.reduce((carry, item) => {
        return item.value === this.modelValue ? item.label : carry;
      }, "");
    },
    /**
     * Фильтруем входной массив data по строке поиска
     * @return string
     */
    computedData() {
      return this.data.filter(
        item =>
          item.label.toLowerCase().indexOf(this.search.toLowerCase()) !== -1
      );
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

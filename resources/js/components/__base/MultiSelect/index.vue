<template>
  <div class="input-select">
    <select
      class="input-select__select"
      :class="computedClassName"
      v-model="computedModelValue"
    >
      <option disabled value>{{ placeholder }}</option>
      <option
        v-for="(item, key) in data"
        :key="key"
        :value="item.value"
        :disabled="item.disabled"
      >
        {{ item.label }}
      </option>
    </select>
  </div>
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
    },
    size: {
      type: String,
      required: false,
      validator: value => {
        return ["slim"].indexOf(value) !== -1;
      }
    },
    placeholder: {
      type: String,
      required: false,
      default: "Выберите из списка"
    }
  },
  computed: {
    /**
     * Получаем название модификатора класса
     * @return string
     */
    computedClassName() {
      switch (this.size) {
        case "slim":
          return "input--slim";
      }
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

<template>
  <div class="input-checkbox" :class="computedClassName">
    <input
      type="checkbox"
      class="input-checkbox__input"
      :id="computedId"
      :disabled="disabled"
      :value="value"
      v-indeterminate="indeterminate"
      v-model="computedModelValue"
    />
    <label
      class="input-checkbox__label"
      :class="{ 'input-checkbox__label--tag': tag }"
      :for="computedId"
      v-if="$slots.hasOwnProperty('default')"
    >
      <slot></slot>
      <span class="input-checkbox__tag" v-if="tag" v-html="tag"></span>
    </label>
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
      type: [Array, Boolean],
      required: true
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    },
    tag: {
      type: String,
      required: false,
      default: ""
    },
    value: {
      type: [String, Boolean],
      required: false
    },
    indeterminate: {
      type: Boolean,
      required: false
    }
  },
  computed: {
    /**
     * Уникальный id
     * @return string
     */
    computedId() {
      return "input-checkbox-" + this._uid;
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
    },
    /**
     * Имя модификатора класса
     * @return string
     */
    computedClassName() {}
  },
  directives: {
    indeterminate: function(el, binding) {
      el.indeterminate = Boolean(binding.value);
    }
  }
};
</script>

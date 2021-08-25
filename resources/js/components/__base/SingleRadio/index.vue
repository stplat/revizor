<template>
  <div class="input-radio" :class="computedClassName">
    <input
      type="radio"
      class="input-radio__input"
      :id="computedId"
      :disabled="disabled"
      :value="value"
      :checked="computedChecked"
      @click="click"
    />
    <label
      class="input-radio__label"
      :class="{ 'input-radio__label--tag': tag }"
      :for="computedId"
      v-if="$slots.hasOwnProperty('default')"
    >
      <slot></slot>
      <span class="input-radio__tag" v-if="tag" v-html="tag"></span>
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
      type: [String, Boolean],
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
    }
  },
  mounted() {
    if (this.isStringHelper(this.modelValue) && !this.value) {
      console.error(
        'Component: SingleRadio. VModel type - String. Missing required prop: "value"'
      );
    }
  },
  computed: {
    /**
     * Уникальный id
     * @return string
     */
    computedId() {
      return "input-radio-" + this._uid;
    },
    /**
     * Имя модификатора класса
     * @return string
     */
    computedClassName() {},
    /**
     * Проверяем выбрана ли данная радио-кнопка
     * @return boolean
     */
    computedChecked() {
      return this.isStringHelper(this.modelValue)
        ? this.value === this.modelValue
        : this.modelValue;
    }
  },
  methods: {
    click(e) {
      if (this.isStringHelper(this.modelValue)) {
        if (this.value) {
          this.$emit(
            "update:modelValue",
            this.modelValue === this.value ? "" : this.value
          );
        }
      } else {
        this.$emit("update:modelValue", !this.modelValue);
      }
    }
  }
};
</script>

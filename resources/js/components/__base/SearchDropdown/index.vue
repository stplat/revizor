<template>
  <div class="dropdown">
    <div class="dropdown__input">
      <input
        class="input"
        :class="{ 'is-active': shown }"
        :id="computedId"
        @focus="shown = true"
        v-model="computedModelValue"
      />
    </div>
    <div class="dropdown__dropdown" v-if="shown">
      <slot></slot>
    </div>
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
    disabled: {
      type: Boolean,
      required: false
    }
  },
  data() {
    return {
      shown: false
    };
  },
  methods: {
    /* Прячем подсказку при клике по документу */
    closeOnDocument(e) {
      if (this.$el !== e.target.closest(".dropdown")) {
        this.shown = false;
      }
    }
  },
  computed: {
    /**
     * Уникальный id
     * @return string
     */
    computedId() {
      return "search-dropdown-" + this._uid;
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
  },
  watch: {
    /* Отслеживаем состояние и навешивание/удаляем обработчик */
    shown(value) {
      if (value && this.disabled !== true) {
        document.addEventListener("click", this.closeOnDocument);
      } else {
        document.removeEventListener("click", this.closeOnDocument);
      }
    }
  }
};
</script>

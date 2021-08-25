<template>
  <div class="dropdown">
    <input
      type="checkbox"
      hidden
      :id="computedId"
      v-model="shown"
      :disabled="disabled"
    />
    <label class="dropdown__btn" :for="computedId">{{ placeHolder }} </label>
    <div class="dropdown__dropdown" v-if="shown">
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    placeHolder: {
      type: String,
      required: false
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
      return "button-dropdown-" + this._uid;
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

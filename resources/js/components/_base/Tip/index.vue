<template>
  <div class="tip">
    <div class="tip__btn" :class="computedButtonClass" @click="shown = !shown">
      <slot name="btn"></slot>
    </div>
    <transition name="fade">
      <div class="tip__dropdown" :class="computedDropdownClass" v-if="shown">
        <div class="tip-triangle" v-if="!direction">
          <div class="tip-triangle__inner"></div>
        </div>
        <div class="tip-triangle-left" v-if="direction === 'left'">
          <div class="tip-triangle-left__inner"></div>
        </div>
        <div class="tip-triangle-right" v-if="direction === 'right'">
          <div class="tip-triangle-right__inner"></div>
        </div>
        <div class="tip-dropdown">
          <a
            href=""
            class="tip-dropdown__close"
            @click.prevent="shown = false"
          ></a>
          <div class="tip-dropdown__head">{{ title }}</div>
          <div class="tip-dropdown__body">
            <preloader v-if="isLoading"></preloader>
            <slot></slot>
          </div>
        </div>
      </div>
    </transition>
    <slot name="outer"></slot>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true
    },
    buttonType: {
      type: String,
      required: false,
      default: "question",
      validator: value => {
        return ["question", "settings", "info"].indexOf(value) !== -1;
      }
    },
    direction: {
      type: String,
      required: false,
      validator: value => {
        return ["left", "right"].indexOf(value) !== -1;
      }
    },
    isLoading: {
      type: Boolean,
      required: false
    }
  },
  data() {
    return {
      shown: false
    };
  },
  computed: {
    computedButtonClass() {
      if (this.$slots.hasOwnProperty("btn")) return "tip__btn--underline";
      if (this.buttonType === "question") return "tip__btn--question";
      if (this.buttonType === "settings") return "tip__btn--settings";
    },
    computedDropdownClass() {
      return this.direction ? `tip__dropdown--${this.direction}` : "";
    }
  },
  watch: {
    /* Отслеживаем состояние и навешивание/удаляем обработчик */
    shown(value) {
      if (value) {
        document.addEventListener("click", this.closeOnDocument);
      } else {
        document.removeEventListener("click", this.closeOnDocument);
      }
    }
  },
  methods: {
    /* Прячем подсказку при клике по документу */
    closeOnDocument(e) {
      if (this.$el !== e.target.closest(".tip")) {
        this.shown = false;
      }
    }
  },
  mounted() {}
};
</script>
<style lang="scss" scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.1s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */
 {
  opacity: 0;
}
</style>

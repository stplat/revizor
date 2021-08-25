<template>
  <transition name="popup" mode="in-out">
    <div
      :id="'popup-' + _uid"
      class="popup"
      ref="popup"
      @click="close"
      v-if="shown"
      :style="{ textAlign: image ? 'center' : 'left', zIndex: zIndex }"
    >
      <div
        class="popup__container"
        :style="{
          width: width
        }"
      >
        <preloader v-if="isLoading"></preloader>
        <div class="popup__close" title="Закрыть" @click="$emit('close')"></div>
        <div class="popup__header" v-if="$slots.hasOwnProperty('header')">
          <div class="popup__header-wrap">
            <div class="popup__sign" v-if="$slots.hasOwnProperty('sign')">
              <slot name="sign"></slot>
            </div>
            <h4 class="popup__title">
              <slot name="header"></slot>
            </h4>
            <div
              class="popup__sub-title"
              v-if="$slots.hasOwnProperty('subheader')"
            >
              <slot name="subheader"></slot>
            </div>
            <div
              class="popup__right-side"
              v-if="$slots.hasOwnProperty('right-side')"
            >
              <slot name="right-side"></slot>
            </div>
          </div>
        </div>
        <div class="popup__body" v-if="$slots.hasOwnProperty('body')">
          <slot name="body"></slot>
        </div>
        <div class="popup__footer" v-if="$slots.hasOwnProperty('footer')">
          <slot name="footer"></slot>
        </div>
      </div>
    </div>
  </transition>
</template>
<script>
import {
  disableBodyScroll,
  enableBodyScroll,
  clearAllBodyScrollLocks
} from "body-scroll-lock";

export default {
  props: {
    shown: {
      type: Boolean,
      required: true,
      default: false
    },
    isLoading: {
      type: Boolean,
      required: false,
      default: false
    },
    image: {
      type: Boolean,
      required: false
    },
    width: {
      type: String,
      required: false
    },
    zIndex: {
      type: Number,
      required: false
    }
  },
  methods: {
    close(e) {
      if (!e.target.closest("#popup-" + this._uid + " .popup__container")) {
        this.$emit("close");
      }
    },
    closeByKeydownEscape(e) {
      if (e.code === "Escape") {
        this.$emit("close");
      }
    },
    addInstance() {
      window.popupInstance = window.popupInstance
        ? window.popupInstance + 1
        : 1;
    },
    subInstance() {
      window.popupInstance--;
    }
  },
  mounted() {
    if (this.shown) {
      this.addInstance();
    }
  },
  watch: {
    /* Отслеживаем состояние и навешивание/удаляем обработчик */
    shown(value) {
      if (value) {
        this.addInstance();
        document.addEventListener("keydown", this.closeByKeydownEscape);
        disableBodyScroll(document);
      } else {
        this.subInstance();
        document.removeEventListener("keydown", this.closeByKeydownEscape);
        if (!window.popupInstance) {
          enableBodyScroll(document);
        }
      }
    }
  }
};
</script>

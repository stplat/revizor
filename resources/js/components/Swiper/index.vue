<template>
  <div class="swiper-container__wrapper">
    <div
      class="swiper-container"
      :id="'swiper_' + _uid"
      @mouseenter="autoPlayStop"
      @mouseleave="autoPlayStart"
    >
      <div class="swiper-wrapper">
        <slot></slot>
      </div>
      <div
        class="swiper-pagination"
        v-if="options.hasOwnProperty('pagination')"
      ></div>
    </div>
    <template v-if="options.hasOwnProperty('navigation')">
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </template>
  </div>
</template>
<script>
import Swiper, { Navigation, Pagination, Autoplay } from "swiper";

export default {
  data() {
    return {
      swiper: null
    };
  },
  props: {
    options: {
      type: Object,
      required: true
    }
  },
  methods: {
    autoPlayStop() {
      this.options.hasOwnProperty("autoplay") && this.swiper
        ? this.swiper.autoplay.stop()
        : "";
    },
    autoPlayStart() {
      this.options.hasOwnProperty("autoplay") && this.swiper
        ? this.swiper.autoplay.start()
        : "";
    }
  },
  beforeUpdate() {
    this.swiper ? this.swiper.destroy() : "";
  },
  updated() {
    this.swiper = new Swiper("#swiper_" + this._uid, this.options);
  },
  mounted() {
    if (this.options.hasOwnProperty("navigation")) {
      Swiper.use([Navigation]);
    }

    if (this.options.hasOwnProperty("pagination")) {
      Swiper.use([Pagination]);
    }

    if (this.options.hasOwnProperty("autoplay")) {
      Swiper.use([Autoplay]);
    }

    setTimeout(() => {
      this.swiper = new Swiper("#swiper_" + this._uid, this.options);
    }, 1000);
  },
  destroyed() {
    if (this.swiper) {
      this.swiper.destroy();
    }
  }
};
</script>
<style lang="scss" scoped>
.swiper-button-next,
.swiper-button-prev {
  &:after {
    content: "\f007";
    font-family: Fontello;
    color: #caa971;
    font-size: 20px;
    transition: color 0.05s;
  }

  .no-touchevents &:hover {
    &:after {
      color: #b5945c;
    }
  }

  @media only screen and (max-width: 768px) {
    display: none;
  }
}

.swiper-button-prev {
  left: 0;
}

.swiper-button-next {
  right: 0;

  &:after {
    content: "\f006";
  }
}
</style>

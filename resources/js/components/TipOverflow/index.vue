<template>
  <div class="tip-overflow">
    <transition name="tip-overflow" mode="in-out">
      <div class="tip-overflow__popup" v-if="computedShown">
        <div class="tip-overflow-triangle"></div>
        {{ computedText }}
      </div>
    </transition>
    <div class="tip-overflow__text" ref="text" :class="computedClassName">
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    text: {
      type: String,
      required: false
    },
    withBorder: {
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
    computedText() {
      return this.text ? this.text : this.$slots.default[0].text;
    },
    computedShown() {
      return (
        (this.shown && this.computedText.length > 35) ||
        (this.shown && this.text)
      );
    },
    computedClassName() {
      if (this.withBorder) return "";
      if (this.computedText.length < 35)
        return "tip-overflow__text--border-none";
      return "";
    }
  },
  methods: {
    showTip() {
      this.shown = true;
    },
    hideTip() {
      this.shown = false;
    }
  },
  mounted() {
    if (
      (this.computedText.length > 35 && this.$refs.hasOwnProperty("text")) ||
      this.text
    ) {
      this.$refs.text.addEventListener("mouseenter", this.showTip);
      this.$refs.text.addEventListener("mouseleave", this.hideTip);
    }
  },
  beforeDestroy() {
    if (this.$refs.hasOwnProperty("text")) {
      this.$refs.text.removeEventListener("mouseenter", this.showTip);
      this.$refs.text.removeEventListener("mouseleave", this.hideTip);
    }
  }
};
</script>

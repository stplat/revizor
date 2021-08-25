<template>
  <div class="card" :class="computedClassName">
    <div class="card__header">
      <h3>
        <slot name="title"></slot>
      </h3>
      <div class="card__header-right">
        <slot name="title-right"></slot>
      </div>
    </div>
    <div class="card__body">
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    size: {
      type: String,
      required: false,
      validator: value => {
        return ["small"].indexOf(value) !== -1;
      }
    },
    disabled: {
      type: Boolean,
      required: false
    }
  },
  computed: {
    computedClassName() {
      switch (this.size) {
        case "small":
          return "card--small";
        default:
          return "";
      }

      // if (this.disabled) {
      //   return "card--disabled";
      // }
    }
  },
  data() {
    return {};
  }
};
</script>

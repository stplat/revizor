<template>
  <div class="toggle">
    <input
      type="checkbox"
      hidden
      :id="'toggle-' + _uid"
      :name="name"
      :value="value"
      :disabled="disabled"
      v-model="vModel"
      @change="$emit('change', $event)"
    />
    <label class="toggle__wrap" :for="'toggle-' + _uid">
      <span class="toggle__tongue"></span>
    </label>
    <label
      class="toggle__label"
      :for="'toggle-' + _uid"
      v-if="$slots.hasOwnProperty('default')"
    >
      <slot></slot>
    </label>
  </div>
</template>
<script>
export default {
  props: {
    value: {
      type: [Boolean, Array],
      required: false
    },
    data: {
      type: Array,
      required: false
    },
    name: {
      type: String,
      required: false
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  computed: {
    vModel: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      }
    }
  }
};
</script>

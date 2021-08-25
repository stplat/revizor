<template>
  <div class="toggle">
    <input
      type="radio"
      hidden
      :id="'toggle-' + _uid"
      :name="name"
      :value="value"
      :checked="checked === value"
      :disabled="disabled"
      @click="input"
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
  model: {
    prop: "checked",
    event: "input"
  },
  props: {
    checked: {},
    value: {
      type: String,
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
    },
    type: {
      type: String,
      required: false,
      default: "checkbox"
    }
  },
  computed: {},
  methods: {
    input(e) {
      if (this.checked !== this.value) {
        this.$emit("input", this.value);
      } else {
        this.$emit("input", "");
      }
    }
  },
  mounted() {}
};
</script>

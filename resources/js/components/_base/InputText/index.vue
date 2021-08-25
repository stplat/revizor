<template>
  <input
    :type="type"
    class="input"
    :value="value"
    :placeholder="placeholder"
    @input="$emit('input', $event.target.value)"
    @focus="$emit('focus', $event)"
    @blur="$emit('blur', $event)"
    :disabled="disabled"
  />
</template>
<script>
export default {
  model: {
    prop: "value",
    event: "input"
  },
  props: {
    type: {
      type: String,
      default: "text",
      required: false,
      validator: value => {
        return ["text", "password"].indexOf(value) !== -1;
      }
    },
    placeholder: {
      type: String,
      required: false
    },
    name: {
      type: String,
      required: false
    },
    value: {
      type: String,
      required: false
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    }
  }
};
</script>

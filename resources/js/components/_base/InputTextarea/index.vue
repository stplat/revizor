<template>
  <textarea
    class="input"
    name=""
    id=""
    cols="30"
    rows="10"
    :value="value"
    :style="{ resize: resize }"
    @input="$emit('input', $event.target.value)"
    :disabled="disabled"
  ></textarea>
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
      required: true
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    },
    resize: {
      type: String,
      required: false,
      default: "none",
      validator: string => {
        return (
          ["none", "both", "horizontal", "vertical", "inherit"].indexOf(
            string
          ) !== -1
        );
      }
    }
  }
};
</script>

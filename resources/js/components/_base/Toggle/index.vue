<template>
  <div class="toggle">
    <input type="checkbox" hidden :id="'toggle-' +_uid"
           :checked="checked"
           :name="name"
           @change="$emit('change', $event.target.checked, $event)"
           :disabled="disabled"
    >
    <label class="toggle__wrap" :for="'toggle-' +_uid">
      <span class="toggle__tongue"></span>
    </label>
    <label class="toggle__label"
           :for="'toggle-' +_uid"
           v-if="$slots.hasOwnProperty('default')">
      <slot></slot>
    </label>
  </div>
</template>
<script>
  export default {
    model: {
      prop: 'checked',
      event: 'change'
    },
    props: {
      checked: {
        type: Boolean,
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
  }
</script>

<template>
  <button type="button" class="hamburger hamburger--arrowturn-r"
          :class="{'is-active': checked}"
          @click="$emit('toggle', !checked)">
    <span class="hamburger-box">
      <span class="hamburger-inner"></span>
    </span>
  </button>
</template>
<script>
  export default {
    model: {
      prop: 'checked',
      event: 'toggle'
    },
    props: {
      checked: {
        type: Boolean,
        required: true
      }
    }
  }
</script>

<template>
  <div class="toggle">
    <input type="checkbox" hidden
           :id="'toggle-' +_uid"
           :value="value"
           v-model="computedChecked"
           :disabled="disabled"
    >
    <label class="toggle__wrap"
           :for="'toggle-' +_uid"
    >
      <span class="toggle__tongue"></span>
    </label>
    <label class="toggle__label"
           :for="'toggle-' +_uid"
           v-if="$slots.hasOwnProperty('default')"
    >
      <slot></slot>
    </label>
  </div>
</template>
<script>
  export default {
    model: {
      prop: 'checkedArray'
    },
    props: {
      checkedArray: {
        type: Array,
        required: false
      },
      value: {
        type: Number,
        required: true
      },
      disabled: {
        type: Boolean,
        required: false,
        default: false
      }
    },
    computed: {
      computedChecked: {
        get() {
          return this.checkedArray;
        },
        set(checkedArray) {
          this.$emit('input', checkedArray);
        }
      }
    }
  }
</script>

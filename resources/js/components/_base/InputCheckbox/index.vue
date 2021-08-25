<template>
  <div class="input-control"
       :class="computedClassName"
  >
    <input type="checkbox" class="input-control__input"
           :id="'checkbox-' +_uid"
           :value="value"
           v-model="computedChecked"
           :disabled="disabled"
           v-indeterminate="indeterminate"
           @mousedown="$emit('mousedown', $event)"
           @mouseup="$emit('mouseup', $event)"
    >
    <label class="input-control__label"
           :class="{'input-control__label--flex': tag}"
           :for="'checkbox-' +_uid"
           v-if="$slots.hasOwnProperty('default')"
    >
      <slot></slot>
      <span class="input-control__tag" v-if="tag" v-html="tag"></span>
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
      type: {
        type: String,
        required: false
      },
      checked: {
        type: [Array, Boolean],
        required: false
      },
      value: {
        type: String,
        required: false
      },
      indeterminate: {
        type: Boolean,
        required: false,
      },
      tag: {
        type: String,
        required: false,
        default: ''
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
          return this.checked;
        },
        set(value) {
          this.$emit('change', value);
        }
      },
      computedClassName() {
        switch (this.type) {
          case 'blue':
            return 'input-control--blue';
        }
      }
    },
    directives: {
      indeterminate: function (el, binding) {
        el.indeterminate = Boolean(binding.value);
      }
    }
  }
</script>

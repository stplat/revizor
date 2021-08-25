<template>
  <div class="input-control"
       :class="computedClassName"
  >
    <input type="radio" class="input-control__input input-control__input--radio"
           :id="'radio-' +_uid"
           :value="value"
           :name="name"
           :checked="checked"
           :disabled="disabled"
           @change="$emit('change', $event.target.value, $event)"
           @mousedown="$emit('mousedown', $event)"
           @mouseup="$emit('mouseup', $event)"
    >
    <label class="input-control__label"
           :class="{'input-control__label--flex': tag}"
           :for="'radio-' +_uid"
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
      name: {
        type: String,
        required: true
      },
      value: {
        type: String,
        required: true
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
      },
      checked: {
        type: Boolean,
        required: false
      }
    },
    computed: {
      computedClassName() {
        switch (this.type) {
          case 'blue':
            return 'input-control--blue';
        }
      }
    },
  }
</script>

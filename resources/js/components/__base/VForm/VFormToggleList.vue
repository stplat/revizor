<template>
  <div class="form-group">
    <label class="form-group__label"
           :for="'form-toggle-' + _uid"
    >{{ label }}</label>
    <div class="form-group__desc" v-if="desc">{{ desc }}</div>
    <toggle-list
      :id="'form-toggle-' + _uid"
      :data="data"
      :value="value"
      @input="change"
    >
      <slot></slot>
    </toggle-list>
  </div>
</template>
<script>
  import formGroup from '@/mixins/formGroup';

  export default {
    mixins: [formGroup],
    props: {
      data: {
        type: Array,
        required: true
      },
      value: {
        type: Object,
        required: false
      }
    },
    methods: {
      change(value, e) {
        this.$emit('input', Object.assign({}, value), e, this.name);
      }
    }
  }
</script>

<template>
  <div class="form-group">
    <label class="form-group__label"
           :for="'form-toggle-' + _uid"
    >{{ label }}</label>
    <div class="form-group__desc" v-if="desc">{{ desc }}</div>
    <toggle-checkbox-list
      :id="'form-toggle-' + _uid"
      :data="data"
      :name="name"
      :value="value"
      @input="change"
    >
      <slot></slot>
    </toggle-checkbox-list>
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
        type: Array,
        required: false
      }
    },
    methods: {
      change(value, e) {
        this.$emit('input', value, e, this.name);
      }
    }
  }
</script>

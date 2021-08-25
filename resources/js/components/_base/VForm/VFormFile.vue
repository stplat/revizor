<template>
  <div class="form-group">
    <label class="form-group__label"
           :for="'form-file-' + _uid"
    >{{ label }}</label>
    <div class="form-group__desc" v-if="desc">{{ desc }}</div>
    <file
      :id="'form-file-' + _uid"
      :name="name"
      :linkToExample="linkToExample"
      @input="change"
    ></file>
  </div>
</template>
<script>
  import formGroup from '@/mixins/formGroup';

  export default {
    mixins: [formGroup],
    props: {
      linkToExample: {
        type: String,
        required: false
      },
      value: {
        // type: File,
        // required: false
      }
    }
  }
</script>

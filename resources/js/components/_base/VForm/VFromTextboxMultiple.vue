<template>
  <div class="form-group">
    <label class="form-group__label"
           :for="'form-input-' + _uid"
    >{{ label }}</label>
    <div class="form-group__desc" v-if="desc">{{ desc }}</div>
    <textbox-multiple
      :name="name"
      :value="value"
      @input="change"
    ></textbox-multiple>
  </div>
</template>
<script>
  import formGroup from "@/mixins/formGroup";

  export default {
    mixins: [ formGroup ],
    props: {
      value: {
        type: Array,
        required: true
      }
    }
  }
</script>

<template>
  <div class="form-group">
    <label class="form-group__label"
           :for="'form-toggle-' + _uid"
    >{{ label }}</label>
    <div class="form-group__desc" v-if="desc">{{ desc }}</div>
    <toggle
      :id="'form-toggle-' + _uid"
      :value="value"
      :name="name"
      @change="change"
    ></toggle>
  </div>
</template>
<script>
  import formGroup from "@/mixins/formGroup";

  export default {
    mixins: [ formGroup ],
    props: {
      value: {
        type: Boolean,
        required: true
      }
    }
  }
</script>

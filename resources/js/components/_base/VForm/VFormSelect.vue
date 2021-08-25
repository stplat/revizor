<template>
  <div class="form-group">
    <label class="form-group__label"
           :for="'form-select-' + _uid"
    >{{ label }}</label>
    <div class="form-group__desc" v-if="desc">{{ desc }}</div>
    <input-select
      :id="'form-select-' + _uid"
      :data="data"
      :value="value"
      :name="name"
      @change="change"
    ></input-select>
  </div>
</template>
<script>
  import formGroup from "@/mixins/formGroup";

  export default {
    mixins: [ formGroup ],
    props: {
      data: {
        type: Array,
        required: true
      }
    }
  }
</script>

<template>
  <div class="form-group">
    <label class="form-group__label" :for="'form-input-' + _uid">{{
      label
    }}</label>
    <div class="form-group__desc" v-if="desc">{{ desc }}</div>
    <m-number
      :id="'form-input-' + _uid"
      :value="value"
      :name="name"
      @input="change"
    ></m-number>
  </div>
</template>
<script>
import formGroup from "@/mixins/formGroup";

export default {
  mixins: [formGroup],
  props: {
    value: {
      type: Number,
      required: false
    }
  },
  mounted() {
    console.log("asd");
  }
};
</script>

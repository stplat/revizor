<template>
  <div class="form-group">
    <label class="form-group__label" :for="computedId">{{
      computedLabel
    }}</label>
    <div class="form-group__desc" v-if="computedDescription">
      {{ computedDescription }}
    </div>
    <m-select
      :id="computedId"
      :data="computedData"
      v-model="computedModelValue"
    ></m-select>
  </div>
</template>
<script>
import formGroup from "@/mixins/formGroup";

export default {
  mixins: [formGroup],
  props: {
    data: {
      type: Object,
      required: true,
      validator: object => {
        return (
          object.hasOwnProperty("type") &&
          object.hasOwnProperty("name") &&
          object.hasOwnProperty("label") &&
          object.hasOwnProperty("data")
        );
      }
    }
  }
};
</script>

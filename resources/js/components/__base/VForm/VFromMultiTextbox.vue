<template>
  <div class="form-group">
    <label class="form-group__label" :for="computedId">{{
      computedLabel
    }}</label>
    <div class="form-group__desc" v-if="computedDescription">
      {{ computedDescription }}
    </div>
    <m-textbox v-model="computedModelValue"></m-textbox>
  </div>
</template>
<script>
import formGroup from "@/mixins/formGroup";

export default {
  mixins: [formGroup]
};
</script>

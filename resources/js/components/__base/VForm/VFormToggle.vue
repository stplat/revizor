<template>
  <div class="form-group">
    <label class="form-group__label" :for="computedId">{{
      computedLabel
    }}</label>
    <div class="form-group__desc" v-if="computedDescription">
      {{ computedDescription }}
    </div>
    <s-radio-toggle
      :id="'form-toggle-' + _uid"
      v-model="computedModelValue"
    ></s-radio-toggle>
  </div>
</template>
<script>
import formGroup from "@/mixins/formGroup";

export default {
  mixins: [formGroup]
};
</script>

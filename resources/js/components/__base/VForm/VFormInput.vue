<template>
  <div class="form-group">
    <label class="form-group__label" :for="computedId">{{
      computedLabel
    }}</label>
    <div class="form-group__desc" v-if="computedDescription">
      {{ computedDescription }}
    </div>
    <input class="input" :id="computedId" v-model="computedModelValue" />
  </div>
</template>
<script>
import formGroup from "@/mixins/formGroup";

export default {
  mixins: [formGroup]
};
</script>

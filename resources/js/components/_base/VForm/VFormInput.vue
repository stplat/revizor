<template>
  <div class="form-group">
    <label class="form-group__label"
           :for="'form-input-' + _uid"
    >{{ label }}</label>
    <div class="form-group__desc" v-if="desc">{{ desc }}</div>
    <input
      class="form-control"
      :id="'form-input-' + _uid"
      :value="value"
      :name="name"
      :type="type"
      @input="change($event.target.value, $event)"
    >
  </div>
</template>
<script>
  import formGroup from "@/mixins/formGroup";

  export default {
    mixins: [ formGroup ]
  }
</script>

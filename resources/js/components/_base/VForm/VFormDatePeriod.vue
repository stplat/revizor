<template>
  <div class="form-group">
    <label class="form-group__label"
           :for="'form-date-period-' + _uid"
    >{{ label }}</label>
    <div class="form-group__desc" v-if="desc">{{ desc }}</div>
    <div class="date-period">
      <div class="date-period__col">
        <input type="date" class="form-control"
               :id="'form-date-period-from' + _uid"
               :value="value.start"
               name="start"
               @input="change($event.target.value, $event)"
        >
      </div>
      <div class="date-period__separate">—</div>
      <div class="date-period__col">
        <input type="date" class="form-control"
               :id="'form-date-period-to' + _uid"
               :value="value.end"
               name="end"
               @input="change($event.target.value, $event)"
        >
      </div>
    </div>
  </div>
</template>
<script>
  import formGroup from "@/mixins/formGroup";

  export default {
    mixins: [ formGroup ],
    props: {
      value: {
        type: Object,
        required: true
      }
    },
    methods: {
      change(value, e) {
        this.$emit('input', Object.assign({}, this.value, {
          [e.target.name]: value
        }), e, this.name);
      }
    }
  }
</script>

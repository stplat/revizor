<template>
  <div class="form-group">
    <label class="form-group__label" :for="computedId">{{
      computedLabel
    }}</label>
    <div class="form-group__desc" v-if="computedDescription">
      {{ computedDescription }}
    </div>
    <div class="date-period">
      <div class="date-period__col">
        <input type="date" class="input" v-model="computedModelValueFrom" />
      </div>
      <div class="date-period__separate">—</div>
      <div class="date-period__col">
        <input type="date" class="input" v-model="computedModelValueTo" />
      </div>
    </div>
  </div>
</template>
<script>
import formGroup from "@/mixins/formGroup";

export default {
  mixins: [formGroup],
  computed: {
    /**
     * Нижний порог для даты
     * @return string
     */
    computedModelValueFrom: {
      get() {
        return this.modelValue[this.data.name].from;
      },
      set(value) {
        const date = Object.assign({}, this.modelValue[this.data.name], {
          from: value
        });

        this.$emit(
          "update:modelValue",
          Object.assign({}, { [this.data.name]: date })
        );
      }
    },
    /**
     * Верхний порог для даты
     * @return string
     */
    computedModelValueTo: {
      get() {
        return this.modelValue[this.data.name].to;
      },
      set(value) {
        const date = Object.assign({}, this.modelValue[this.data.name], {
          to: value
        });

        this.$emit(
          "update:modelValue",
          Object.assign({}, { [this.data.name]: date })
        );
      }
    }
  }
};
</script>

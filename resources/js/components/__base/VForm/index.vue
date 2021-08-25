<template>
  <div class="form">
    <div
      class="form__group"
      v-for="(group, key) in data"
      :key="_uid + '-' + group.name + '-' + key"
    >
      <form-select
        v-if="group.type === 'select'"
        :data="group"
        v-model="computedModelValue"
      >
      </form-select>
      <form-input
        v-if="group.type === 'text' || group.type === 'password'"
        :data="group"
        v-model="computedModelValue"
      ></form-input>
      <v-form-date-period
        v-if="group.type === 'date-period'"
        :data="group"
        v-model="computedModelValue"
      ></v-form-date-period>
      <v-form-multi-textbox
        v-if="group.type === 'multi-textbox'"
        :data="group"
        v-model="computedModelValue"
      ></v-form-multi-textbox>
      <v-form-toggle
        v-if="group.type === 'toggle'"
        :data="group"
        v-model="computedModelValue"
      ></v-form-toggle>
      <v-form-file
        v-if="group.type === 'file'"
        :data="group"
        v-model="computedModelValue"
      ></v-form-file>
      <!--<form-number
        v-if="group.type === 'number'"
        :label="group.label"
        :name="group.name"
        :desc="group.desc"
        :value="value[group.name]"
        @input="change"
      ></form-number>
      <form-toggle-list
        v-if="group.type === 'toggle-list'"
        :label="group.label"
        :name="group.name"
        :desc="group.desc"
        :data="group.data"
        :value="value[group.name]"
        @input="change"
      ></form-toggle-list>
      <form-toggle-checkbox-list
        v-if="group.type === 'toggle-list-checkbox'"
        :label="group.label"
        :name="group.name"
        :desc="group.desc"
        :data="group.data"
        :value="value[group.name]"
        @input="change"
      ></form-toggle-checkbox-list>


      ></v-from-textbox-multiple>

       -->
    </div>
  </div>
</template>
<script>
export default {
  components: {
    VFormFile: () => import("./VFormFile"),
    VFormToggle: () => import("./VFormToggle"),
    VFormMultiTextbox: () => import("./VFromMultiTextbox"),
    VFormDatePeriod: () => import("./VFormDatePeriod"),
    FormSelect: () => import("./VFormSelect"),
    FormInput: () => import("./VFormInput"),
    FormNumber: () => import("./VFormNumber"),
    FormToggleList: () => import("./VFormToggleList"),
    FormToggleCheckboxList: () => import("./VFormToggleCheckboxList")
  },
  model: {
    prop: "modelValue",
    event: "update:modelValue"
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    },
    data: {
      type: Array,
      required: true
    }
  },
  computed: {
    /**
     * Геттер и сеттер для модели
     * @return string
     */
    computedModelValue: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit(
          "update:modelValue",
          Object.assign({}, this.modelValue, value)
        );
      }
    }
  }
};
</script>

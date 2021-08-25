<template>
  <div class="form">
    <div
      class="form__group"
      v-for="(group, key) in forms"
      :key="_uid + '-' + group.name + '-' + key"
    >
      <form-select
        v-if="group.type === 'select'"
        :label="group.label"
        :name="group.name"
        :desc="group.desc"
        :data="group.data"
        :value="value[group.name]"
        @input="change"
        >{{ group.label }}
      </form-select>
      <form-input
        v-if="group.type === 'text' || group.type === 'password'"
        :type="group.type"
        :label="group.label"
        :name="group.name"
        :desc="group.desc"
        :value="value[group.name]"
        @input="change"
      ></form-input>
      <form-number
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
      <v-form-date-period
        v-if="group.type === 'date-period'"
        :label="group.label"
        :name="group.name"
        :desc="group.desc"
        :value="value[group.name]"
        @input="change"
      ></v-form-date-period>
      <v-from-textbox-multiple
        v-if="group.type === 'textbox-multiple'"
        :label="group.label"
        :name="group.name"
        :desc="group.desc"
        :value="value[group.name]"
        @input="change"
      ></v-from-textbox-multiple>
      <v-form-toggle
        v-if="group.type === 'toggle'"
        :label="group.label"
        :name="group.name"
        :desc="group.desc"
        :value="value[group.name]"
        @input="change"
      ></v-form-toggle>
      <v-form-file
        v-if="group.type === 'file'"
        :label="group.label"
        :name="group.name"
        :linkToExample="group.linkToExample"
        :desc="group.desc"
        :value="value[group.name]"
        @input="change"
      ></v-form-file>
    </div>
  </div>
</template>
<script>
export default {
  components: {
    VFormFile: () => import("./VFormFile"),
    VFormToggle: () => import("./VFormToggle"),
    VFromTextboxMultiple: () => import("./VFromTextboxMultiple"),
    VFormDatePeriod: () => import("./VFormDatePeriod"),
    FormSelect: () => import("./VFormSelect"),
    FormInput: () => import("./VFormInput"),
    FormNumber: () => import("./VFormNumber"),
    FormToggleList: () => import("./VFormToggleList"),
    FormToggleCheckboxList: () => import("./VFormToggleCheckboxList")
  },
  props: {
    forms: {
      type: Array,
      required: true
    },
    value: {
      type: Object,
      required: true
    }
  },
  methods: {
    change(value, e = null, name = "") {
      this.$emit(
        "input",
        Object.assign({}, this.value, {
          [name ? name : e.target.name]: value
        })
      );
    }
  }
};
</script>

<template>
  <select
    ref="select"
    class="input"
    :class="computedClassName"
    :id="id"
    :value="value"
    :name="name"
    :disabled="disabled"
    @change="$emit('change', $event.target.value, $event)"
  >
    <option disabled value>{{ placeholder }}</option>
    <option
      v-for="(item, key) in data"
      :key="key"
      :value="item.value"
      :disabled="item.disabled"
    >
      {{ item.name }}
    </option>
  </select>
</template>
<script>
export default {
  model: {
    prop: "value",
    event: "change"
  },
  props: {
    id: {
      type: String,
      required: false
    },
    disabled: {},
    name: {
      type: String,
      required: false
    },
    data: {
      type: Array,
      required: true,
      validator: array => {
        return array.every(
          item => item.hasOwnProperty("value") && item.hasOwnProperty("name")
        );
      }
    },
    value: {
      type: String,
      required: false
    },
    size: {
      type: String,
      required: false,
      validator: value => {
        return ["slim"].indexOf(value) !== -1;
      }
    },
    placeholder: {
      type: String,
      required: false,
      default: "Выберите из списка"
    }
  },
  watch: {
    data(value) {
      this.$refs.select.value = this.value;
    }
  },
  computed: {
    computedClassName() {
      switch (this.size) {
        case "slim":
          return "input--slim";
      }
    }
  },
  mounted() {}
};
</script>

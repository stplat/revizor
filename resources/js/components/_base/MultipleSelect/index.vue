<template>
  <dropdown
    :label="computedLabel"
    :disabled="!data.length || disabled"
    :id="id"
    :size="size"
    :color="color"
    :hide="hide"
  >
    <ul class="dropdown__options">
      <li class="dropdown__option" v-for="(item, key) in data" :key="key">
        <input
          type="radio"
          hidden
          :id="item.name + '-option-' + key"
          :name="item.name"
          :value="item.value"
          :checked="item.value === value"
          :disabled="item.disabled"
          @change="change"
        />
        <label :for="item.name + '-option-' + key">{{ item.label }}</label>
      </li>
    </ul>
  </dropdown>
</template>
<script>
export default {
  props: {
    id: {
      type: String,
      required: false
    },
    data: {
      type: Array,
      required: true
    },
    value: {
      type: String,
      required: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    placeholder: {
      type: String,
      required: false,
      default: ""
    },
    size: {
      type: String,
      required: false,
      validator: value => {
        return ["slim"].indexOf(value) !== -1;
      }
    },
    color: {
      type: String,
      required: false,
      validator: value => {
        return (
          [
            "red",
            "green",
            "yellow",
            "orange",
            "grey",
            "dark-grey",
            "blue"
          ].indexOf(value) !== -1
        );
      }
    }
  },
  data() {
    return {
      hide: false
    };
  },
  computed: {
    computedLabel() {
      const label = this.data.reduce((carry, item) => {
        return item.value === this.value ? item.label : carry;
      }, "");

      return this.value ? label : this.placeholder;
    }
  },
  methods: {
    change(e) {
      this.$emit("input", e.target.value, e);
      this.hide = !this.hide;
    }
  }
};
</script>

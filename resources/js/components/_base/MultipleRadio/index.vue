<template>
  <dropdown
    :label="computedLabel"
    :disabled="!data.length"
    :id="id"
    :size="size"
  >
    <ul class="dropdown__options">
      <li class="dropdown__option" v-for="(item, key) in data" :key="key">
        <input-radio
          :name="item.name"
          :value="item.value"
          :checked="item.value === value"
          :disabled="item.disabled"
          :tag="item.tag"
          @change="change"
          >{{ item.label }}
        </input-radio>
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
    }
  },
  computed: {
    computedLabel() {
      return this.value
        ? this.data.filter(item => item.value === this.value)[0].label
        : this.placeholder;
    }
  },
  methods: {
    change(value, e) {
      this.$emit("input", value, e);
    }
  }
};
</script>

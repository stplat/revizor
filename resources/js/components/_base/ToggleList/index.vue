<template>
  <dropdown :label="computedLabel" :isEmpty="!data.length" :id="id">
    <ul class="dropdown__options">
      <li class="dropdown__option"
          v-for="(item, key) in data"
          :key="key"
      >
        <toggle
          :name="item.name"
          :checked="value ? value[item.name] : item.value"
          @change="changeToggle"
          :disabled="!value"
        >{{ item.label }}
        </toggle>
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
        type: Object,
        required: false
      }
    },
    computed: {
      computedLabel() {
        return this.$slots.hasOwnProperty('default') ?
          this.$slots.default[0].text.replace(/ +/g, ' ').trim() :
          'Cписок';
      }
    },
    methods: {
      changeToggle(checked, e) {
        this.$emit('input', Object.assign({}, this.value, {
            [e.target.name]: checked
          }
        ), e);
      }
    }
  }
</script>

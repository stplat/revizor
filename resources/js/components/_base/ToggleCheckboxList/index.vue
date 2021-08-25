<template>
  <dropdown :label="computedLabel" :isEmpty="!data.length" :id="id">
    <ul class="dropdown__options">
      <li class="dropdown__option"
          v-for="(item, key) in data"
          :key="key"
      >
        <toggle-checkbox
          :value="item.id"
          v-model="computedCheckedArray"
          :disabled="!value"
        >{{ item.name }}
        </toggle-checkbox>
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
      name: {
        type: String,
        required: false
      },
      data: {
        type: Array,
        required: true
      },
      value: {
        type: Array,
        required: false
      }
    },
    data() {
      return {
        localArray: []
      }
    },
    computed: {
      computedLabel() {
        return this.$slots.hasOwnProperty('default') ?
          this.$slots.default[0].text.replace(/ +/g, ' ').trim() :
          'Cписок';
      },
      computedCheckedArray: {
        get() {
          return this.value ?? this.localArray;
        },
        set(checkedArray) {
          this.$emit('input', checkedArray, null, this.name);
        }
      }
    },
    mounted() {
      if (!this.value) {
        this.localArray = this.data.map(item => item.id);
      }
    }
  }
</script>

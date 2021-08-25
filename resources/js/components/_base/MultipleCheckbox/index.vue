<template>
  <dropdown type="search" v-model="search">
    <ul class="multiple-checkbox">
      <li class="multiple-checkbox__all">
        <input-checkbox
          v-model="checkedAll"
          :indeterminate="indeterminate"
          @change="toggle"
        >Выбрать все
        </input-checkbox>
      </li>
      <li class="multiple-checkbox__item"
          v-for="(item, key) in computedData"
          :key="key">
        <input-checkbox
          :value="item.value"
          :disabled="item.disabled"
          :tag="item.tag"
          v-model="computedChecked"
        >{{ item.label }}
        </input-checkbox>
      </li>
    </ul>
  </dropdown>
</template>
<script>
  export default {
    props: {
      data: {
        type: Array,
        required: true
      },
      value: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        checkedAll: false,
        indeterminate: false,
        search: ''
      }
    },
    mounted() {
        this.checkedAll = this.data.every(item => this.value.indexOf(item.value) !== -1);
    },
    computed: {
      computedChecked: {
        get() {
          return this.value;
        },
        set(value) {
          this.$emit('input', value);
        }
      },
      computedData() {
        return this.data.filter(item => item.label.toLowerCase().indexOf(this.search.toLowerCase()) !== -1);
      }
    },
    watch: {
      computedChecked(value) {
        if (!value.length) {
          this.indeterminate = false;
          this.checkedAll = false;
        } else if (value.length === this.data.length) {
          this.indeterminate = false;
          this.checkedAll = true;
        } else {
          this.indeterminate = true;
          this.checkedAll = false;
        }
      },
    },
    methods: {
      toggle() {
        this.$emit('input', this.checkedAll ? this.data.map(item => item.id) : []);
      }
    }
  }
</script>

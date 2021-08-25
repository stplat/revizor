<template>
  <ul class="input-checkbox-multi-xls">
    <li class="input-checkbox-multi-xls__all" v-if="data.length">
      <input-checkbox
        v-model="checkedAll"
        :indeterminate="indeterminate"
        @change="changeToggle"
        >Выбрать все
      </input-checkbox>
    </li>
    <li v-if="!data.length">Ничего не найдено</li>
    <li
      class="input-checkbox-multi-xls__item"
      v-for="(item, key) in data"
      :key="'multi-radio-' + key"
    >
      <s-checkbox
        v-model="computedModelValue"
        :value="item.value"
        :name="item.hasOwnProperty('name') ? item.name : computedName"
        :tag="item.tag"
        :disabled="item.disabled"
        >{{ item.label }}</s-checkbox
      >
    </li>
  </ul>
</template>
<script>
export default {
  model: {
    prop: "modelValue",
    event: "update:modelValue"
  },
  props: {
    modelValue: {
      type: Array,
      required: true
    },
    data: {
      type: Array,
      required: true,
      validator: array => {
        return array.every(
          item => item.hasOwnProperty("value") && item.hasOwnProperty("label")
        );
      }
    }
  },
  data() {
    return {
      checkedAll: false,
      indeterminate: false
    };
  },
  mounted() {
    this.checkedAll = this.data.every(
      item => this.modelValue.indexOf(item.value) !== -1
    );

    if (!this.checkedAll) {
      this.indeterminate = this.data.some(
        item => this.modelValue.indexOf(item.value) !== -1
      );
    }
  },
  watch: {
    computedModelValue(value) {
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
    }
  },
  computed: {
    /**
     * Уникальное имя для группы radio-кнопок
     * @return string
     */
    computedName() {
      return "multi-checkbox-" + this._uid;
    },
    /**
     * Геттер и сеттер для модели
     * @return string
     */
    computedModelValue: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit("update:modelValue", value);
      }
    }
  },
  methods: {
    /**
     * Событие "Выбрать все / Отчключить все"
     * @return string
     */
    changeToggle() {
      this.$emit(
        "update:modelValue",
        this.checkedAll ? this.data.map(item => item.value) : []
      );
    }
  }
};
</script>

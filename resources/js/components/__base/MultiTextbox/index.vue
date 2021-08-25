<template>
  <div class="multi-textbox">
    <div
      class="multi-textbox__input"
      v-for="(item, key) in list"
      :key="'multi-textbox' + _uid + key"
    >
      <input
        class="input"
        :id="item.id"
        :value="item.value"
        @input="inputModelValue"
      />
      <button
        class="multi-textbox__btn"
        title="Добавить ссылку"
        v-if="item.id === 1"
        @click.stop="addTextbox"
      ></button>
      <button
        class="multi-textbox__btn multi-textbox__btn--close"
        title="Удалить ссылку"
        v-if="item.id !== 1"
        @click.stop="removeTextbox(item.id)"
      ></button>
    </div>
  </div>
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
    }
  },
  data() {
    return {
      list: [{ id: 1, value: "" }]
    };
  },
  methods: {
    /**
     * События изменения значения
     * @return void
     */
    inputModelValue(e) {
      this.list = this.list.map(item => {
        if (Number(item.id) === Number(e.target.id)) {
          item.value = e.target.value;
          return item;
        }

        return {
          id: item.id,
          value: item.value
        };
      });

      const value = this.list.map(item => item.value).filter(item => item);

      this.$emit("update:modelValue", value);
    },
    /**
     * Добавляем еще одно текстовое поле
     * @return void
     */
    addTextbox() {
      const arrayLength = this.list.length;
      this.list.splice(arrayLength, 1, { id: arrayLength + 1, value: "" });
    },
    /**
     * Удаляем одно текстовое поле
     * @return void
     */
    removeTextbox(id) {
      const index = this.list.findIndex(item => item.id === id);
      this.list.splice(index, 1);
      this.$emit(
        "input",
        this.list.map(item => item.value).filter(item => item),
        null,
        this.name
      );
    }
  }
};
</script>

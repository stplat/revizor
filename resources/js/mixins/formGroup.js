export default {
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
      type: Object,
      required: true,
      validator: object => {
        return (
          object.hasOwnProperty("type") &&
          object.hasOwnProperty("name") &&
          object.hasOwnProperty("label")
        );
      }
    }
  },
  computed: {
    /**
     * Уникальный id
     * @return string
     */
    computedId() {
      return `form-${this.data.type}-${this._uid}`;
    },
    /**
     * Геттер и сеттер для модели
     * @return string
     */
    computedModelValue: {
      get() {
        return this.modelValue[this.data.name];
      },
      set(value) {
        this.$emit(
          "update:modelValue",
          Object.assign(
            {},
            {
              [this.data.name]: value
            }
          )
        );
      }
    },
    /**
     * Описание под меткой
     * @return string
     */
    computedDescription() {
      return this.data.desc;
    },
    /**
     * Метка
     * @return string
     */
    computedLabel() {
      return this.data.label;
    },
    /**
     * Данный для списка
     * @return array
     */
    computedData() {
      return this.data.data;
    }
  },
}

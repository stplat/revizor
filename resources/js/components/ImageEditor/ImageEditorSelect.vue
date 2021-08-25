<template>
  <div class="image-editor-select">
    <input
      type="checkbox"
      hidden
      :id="computedId"
      @change="shown = !shown"
      :disabled="disabled"
    />
    <label
      class="image-editor-btn image-editor-btn--multi "
      :class="[{ 'is-active': shown }, computedBtnClassName]"
      :title="computedTitle"
      :for="computedId"
    ></label>
    <div class="image-editor-select__dropdown" v-if="shown">
      <ul class="image-editor-select__options">
        <li v-for="(item, key) in data" :key="key">
          <input
            type="radio"
            hidden
            :id="'image-editor-radio-' + key"
            v-model="computedModelValue"
            :value="item.value"
            :disabled="item.disabled"
          />
          <label :for="'image-editor-radio-' + key">{{ item.label }}</label>
        </li>
      </ul>
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
      type: String,
      required: false
    },
    data: {
      type: Array,
      required: false,
      validator: array => {
        return array.every(
          item => item.hasOwnProperty("value") && item.hasOwnProperty("label")
        );
      },
      default: () => {
        return [
          { value: "ballot_box", label: "Избирательный ящик" },
          { value: "koib", label: "КОИБ" },
          { value: "n/d", label: "н/д" }
        ];
      }
    },
    button: {
      type: String,
      required: false,
      validator: value => {
        return ["box", "add"].indexOf(value) !== -1;
      },
      default: "add"
    },
    disabled: {
      type: Boolean,
      required: false
    }
  },
  watch: {
    /* Отслеживаем состояние и навешивание/удаляем обработчик */
    shown(value) {
      if (value) {
        document.addEventListener("click", this.closeOnDocument);
      } else {
        document.removeEventListener("click", this.closeOnDocument);
      }
    }
  },
  data() {
    return {
      shown: false
    };
  },
  computed: {
    /**
     * Подписываем кнопку при наведении
     * @return string
     */
    computedTitle() {
      return this.button === "add" ? "Добавить ящик" : "Тип ящика";
    },
    /**
     * Меняем класс для кнопки
     * @return string
     */
    computedBtnClassName() {
      return this.button === "add" ? "" : "image-editor-btn--box";
    },
    /**
     * Уникальный id
     * @return string
     */
    computedId() {
      return "image-editor-select-" + this._uid;
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
        this.shown = false;
        this.$emit("change", value);
        this.$emit("update:modelValue", value);
      }
    }
  },
  methods: {
    /* Прячем подсказку при клике по документу */
    closeOnDocument(e) {
      if (this.$el !== e.target.closest(".image-editor-select")) {
        this.shown = false;
      }
    },
    change(e) {
      this.shown = false;
      this.$emit("change", e.target.value, e);
    }
  }
};
</script>

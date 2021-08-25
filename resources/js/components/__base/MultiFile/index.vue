<template>
  <div class="file">
    <div class="file__title" v-if="$slots.hasOwnProperty('default')">
      <slot></slot>
    </div>
    <alert :messages="errors"></alert>
    <div class="file__desc">
      Загрузите файл с компьютера. Ограничения:
      <p>
        - формат файла: <strong>{{ format }}</strong>
      </p>
      <template v-if="format === 'csv'">
        <p>- кодировка файла: <strong>UTF-8</strong></p>
        <p>- разделитель: <strong>точка с запятой ";"</strong></p>
        <p>- первая строка: <strong>Заголовки</strong></p>
      </template>
      <p v-if="linkToExample">
        - <a :href="linkToExample" download>образец файла</a>
      </p>
    </div>
    <div class="file__input">
      <div class="file__board">
        <p>{{ computedFileName }}</p>
        <div @click="clickDelete" class="file__delete"></div>
      </div>
      <label :for="computedId" class="btn btn--secondary">Выбрать файл</label>
      <input
        hidden
        type="file"
        ref="file"
        :id="computedId"
        @change="changeModelValue"
      />
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
      type: [String, Blob],
      required: false
    },
    linkToExample: {
      type: String,
      required: false
    },
    value: {
      type: [String, Blob],
      required: false
    },
    format: {
      type: String,
      required: false,
      default: "csv",
      validator: value => {
        return ["csv", "pdf", "response"].indexOf(value) !== -1;
      }
    }
  },
  data() {
    return {
      id: null,
      upload: {
        name: ""
      },
      errors: []
    };
  },
  mounted() {
    // this.upload.name = this.value.name;
  },
  methods: {
    /* Валидация входных данных */
    validateFormatFile(file) {
      const validation = this.computedAllowFormats.find(
        item => item === file.type
      );

      this.errors = !validation
        ? [`Допустимый формат файлов: <strong>${this.format}</strong`]
        : [];
      return !this.errors.length;
    },
    /**
     * Добавляем файл
     * @return string
     */
    changeModelValue(e) {
      const file = e.target.files[0];

      if (this.validateFormatFile(file)) {
        this.$emit("update:modelValue", file);
      }
    },
    /**
     * Удаляем файл
     * @return string
     */
    clickDelete(e) {
      this.$emit("update:modelValue", "");
    }
  },
  computed: {
    /**
     * Уникальный id
     * @return string
     */
    computedId() {
      return `multi-file-${this._uid}`;
    },
    /**
     * Имя файла
     * @return string
     */
    computedFileName() {
      return this.modelValue
        ? this.modelValue.name
        : "Не выбрано ни одного файла";
    },
    /**
     * Допустимые форматы файла
     * @return array
     */
    computedAllowFormats() {
      switch (this.format) {
        case "pdf":
          return ["application/pdf"];
        case "response":
          return [
            "application/pdf",
            "image/png",
            "image/gif",
            "image/tiff",
            "image/bmp",
            "image/jpg",
            "image/jpeg"
          ];
        default:
          return [
            "text/csv",
            "application/csv",
            "text/comma-separated-values",
            "application/excel",
            "application/vnd.ms-excel",
            "application/vnd.msexcel",
            "text/anytext",
            "application/octet-stream"
          ];
      }
    }
  }
};
</script>

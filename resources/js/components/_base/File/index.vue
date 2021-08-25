<template>
  <div class="file">
    <div class="file__title" v-if="$slots.hasOwnProperty('default')">
      <slot></slot>
    </div>
    <alert :messages="errors"></alert>
    <div class="file__desc">
      Загрузите файл с компьютера. Ограничения:
      <p>
        - формат файла: <strong>{{ computedFormat }}</strong>
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
        <p>{{ upload.name ? upload.name : "Ни одного файла не выбрано" }}</p>
        <div @click="clickDelete" class="file__delete"></div>
      </div>
      <label :for="'file-upload-' + _uid" class="btn btn--secondary"
        >Выбрать файл</label
      >
      <input
        hidden
        type="file"
        :id="'file-upload-' + _uid"
        :name="name"
        ref="file"
        @change="changeFile"
      />
    </div>
  </div>
</template>
<script>
export default {
  props: {
    linkToExample: {
      type: String,
      required: false
    },
    value: {
      type: [String, Blob],
      required: false
    },
    name: {
      type: String,
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
    this.upload.name = this.value.name;
  },
  methods: {
    /* Валидация входных данных */
    validate(file) {
      const errors = [];

      if (file === "") {
        errors.push(
          "Поле <strong>Файл для импорта</strong> обязательно для заполнения"
        );
      } else {
        const validFormat = this.computedAllowFormat.findIndex(
          item => item === file.type
        );

        if (validFormat === -1) {
          errors.push(
            `Допустимый формат файлов: <strong>${
              this.format === "response" ? this.computedFormat : this.format
            }</strong>`
          );
        }
      }

      this.errors = errors.slice();
      return !errors.length;
    },
    /* Событие изменения (загрузки) файла */
    changeFile(e) {
      const file = e.target.files[0];
      if (this.validate(file)) {
        this.$emit("input", file, e);
        this.upload.name = file.name;
      }
    },
    clickDelete(e) {
      this.$emit("input", "", e);
      this.upload.name = "";
    }
  },
  computed: {
    computedFormat() {
      return this.format === "response"
        ? "pdf, png, gif, tiff, bmp, jpg, jpeg"
        : this.format;
    },
    computedAllowFormat() {
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

<template>
  <popup @close="$emit('close')" :isLoading="isLoading" :shown="shown">
    <template v-slot:header>Загружка ответа</template>
    <template v-slot:body>
      <alert :messages="errors.length ? errors : message"></alert>
      <file v-model="file" format="response">Файл для импорта </file>
    </template>
    <template v-slot:footer>
      <button class="btn btn--primary ml-auto" @click="upload">
        Прикрепить
      </button>
    </template>
  </popup>
</template>
<script>
export default {
  props: {
    shown: {
      type: Boolean,
      required: true
    },
    value: {
      type: [String, Blob],
      required: false
    }
  },
  data() {
    return {
      isLoading: false,
      errors: [],
      message: [],
      file: ""
    };
  },
  watch: {
    shown() {
      this.errors = [];
    }
  },
  methods: {
    /* Загрузка жалобы */
    upload() {
      if (this.file) {
        const violation = this.$store.getters["Modal/getViolation"];

        this.isLoading = true;
        this.$store
          .dispatch("Modal/uploadClaimResponse", {
            file: this.file,
            violation: violation.id
          })
          .then(res => {
            if (res.hasOwnProperty("errors")) {
              this.errors = res.errors;
            } else {
              this.errors = [];
              this.$emit("result", {
                text: "Ответ успешно прикреплен!",
                className: "alert--success"
              });

              this.$emit("close");
            }
            this.isLoading = false;
          });
      } else {
        this.errors = [
          "Поле <strong>Файл для импорта</strong> обязательно для заполнения"
        ];
      }
    }
  }
};
</script>

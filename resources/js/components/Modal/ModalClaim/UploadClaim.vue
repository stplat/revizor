<template>
  <popup @close="$emit('close')" :isLoading="isLoading" :shown="shown">
    <template v-slot:header>Загрузка жалобы</template>
    <template v-slot:body>
      <alert :messages="errors.length ? errors : message"></alert>
      <file v-model="computedFile" format="pdf">Файл для импорта </file>
    </template>
    <template v-slot:footer>
      <button class="btn btn--primary ml-auto" @click="upload">
        Загрузить
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
      message: []
    };
  },
  watch: {
    shown() {
      this.errors = [];
    }
  },
  computed: {
    computedFile: {
      get() {
        return this.value;
      },
      set(file) {
        this.$emit("input", file);
      }
    }
  },
  methods: {
    /* Загрузка жалобы */
    upload() {
      if (this.value) {
        this.$emit("close");
      } else {
        this.errors = ["Файл для импорта не выбран"];
      }
    }
  }
};
</script>

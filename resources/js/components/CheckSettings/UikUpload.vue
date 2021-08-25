<template>
  <popup @close="$emit('close')" :isLoading="isLoading" :shown="shown">
    <template v-slot:header>Импорт участков</template>
    <template v-slot:body>
      <alert :messages="errors.length ? errors : message"></alert>
      <file
        :linkToExample="asset('storage/upload_layouts/stations_example.csv')"
        v-model="data.file"
      >Файл для импорта
      </file>
    </template>
    <template v-slot:footer>
      <button class="btn btn--primary ml-auto" @click="upload">Загрузить</button>
    </template>
  </popup>
</template>
<script>
  export default {
    props: {
      shown: {
        type: Boolean,
        required: true
      }
    },
    data() {
      return {
        data: {
          file: ''
        },
        isLoading: false,
        errors: [],
        message: []
      }
    },
    computed: {
      computedCheck() {
        return this.$store.getters['ViewCheck/getCheck'];
      }
    },
    methods: {
      /* Загрузка перевозчика */
      upload() {
        let { file } = this.data;
        const check_id = this.computedCheck.check_id;

        this.isLoading = true;

        this.$store.dispatch('ViewCheck/uploadUik', { check_id, file }).then(res => {
          if (res.hasOwnProperty('errors')) {
            this.errors = res.errors;
          } else {
            this.errors = [];
            this.message = [ {
              className: 'alert--success',
              text: '<strong>Файл успешно загружен!</strong>'
            } ];
          }
          this.isLoading = false;
        });
      }
    }
  }
</script>

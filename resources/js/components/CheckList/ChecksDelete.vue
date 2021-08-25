<template>
  <popup @close="$emit('close')" :isLoading="isLoading" :shown="shown">
    <template v-slot:header>Удаление проверки</template>
    <template v-slot:body>
      <alert :messages="errors.length ? errors : computedMessage"></alert>
      <h4>Вы уверены, что хотите удалить этого пользователя?</h4>
    </template>
    <template v-slot:footer>
      <button class="btn btn--light ml-auto" @click="$emit('close')">Отменить</button>
      <button class="btn btn--danger ml-1" @click="remove">Удалить</button>
    </template>
  </popup>
</template>
<script>
  export default {
    data() {
      return {
        isLoading: false,
        shown: false,
        errors: []
      }
    },
    props: {
      id: {
        type: Number,
        required: true
      }
    },
    mounted() {
      setTimeout(() => this.shown = true);
    },
    computed: {
      computedMessage() {
        return [
          {
            text: `ID: <strong>${ this.id }</strong>`,
            className: 'alert--info'
          }
        ];
      }
    },
    methods: {
      remove() {
        this.isLoading = true;

        this.$store.dispatch('ViewCheckList/deleteCheck', { id: this.id }).then((res) => {
          if (res.hasOwnProperty('errors')) {
            this.errors = res.errors;
          } else {
            this.errors = [];
            this.$emit('result', {
              text: `Проверка с ID: <strong>${ this.id }</strong> успешно удалена!`,
              className: 'alert--success'
            });
            this.$emit('close');
          }

          this.isLoading = false;
        });
      }
    }

  }
</script>

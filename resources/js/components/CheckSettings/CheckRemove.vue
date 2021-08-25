<template>
  <popup @close="$emit('close')" :isLoading="isLoading" :shown="shown">
    <template v-slot:header>Удаление проверки</template>
    <template v-slot:body>
      <alert :messages="errors.length ? errors : computedMessage"></alert>
      <h4>Вы уверены, что хотите удалить данную проверку?</h4>
    </template>
    <template v-slot:footer>
      <button class="btn btn--light ml-auto" @click="$emit('close')">Отменить</button>
      <button class="btn btn--danger ml-1" @click="removeCheck">Удалить</button>
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
        isLoading: false,
        errors: []
      }
    },
    computed: {
      computedMessage() {
        const check = this.$store.getters['ViewCheck/getCheck'];

        return [
          {
            text: `ID: <strong>${check.check_id}</strong>, название: <strong>${check.check_name}</strong>`,
            className: 'alert--info',
          }
        ];
      }
    },
    methods: {
      /* Удаление проверки */
      removeCheck() {
        const id = this.$store.getters['ViewCheck/getCheck'].check_id;

        this.$store.dispatch('ViewCheck/deleteCheck', { id }).then((res) => {
          if (!res.hasOwnProperty('errors')) {
            document.location.href = this.asset('checks');
          } else {
            this.errors = res.errors;
          }
        });
      },
    }
  }
</script>

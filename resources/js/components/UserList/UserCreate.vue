<template>
  <popup @close="$emit('close')" :isLoading="isLoading" :shown="shown">
    <template v-slot:header>Добавление новых пользователей</template>
    <template v-slot:body>
      <alert :messages="errors"></alert>
      <v-form :forms="computedForm" v-model="data"></v-form>
    </template>
    <template v-slot:footer>
      <button class="btn btn--primary ml-auto" @click="create">Добавить</button>
    </template>
  </popup>
</template>

<script>
  import formValidation from "@/mixins/formValidation";

  export default {
    mixins: [ formValidation ],
    mounted() {
      setTimeout(() => this.shown = true);
      const checks = this.$store.getters['ViewUser/getCheckList'];

      let maxCheckId = 0;

      checks.forEach(item => {
        if (item.id > maxCheckId) maxCheckId = item.id;
      });

      this.data.checks = [ maxCheckId ];
    },
    data() {
      return {
        data: {
          user_quantity: 1,
          role_id: '',
          permissions: {
            detections_check_access: true,
            uiks_view_access: true,
            events_view_access: true,
            events_form_access: true,
            stats_view_access: true
          },
          checks: []
        },
        rules: {
          user_quantity: [ 'required' ],
          role_id: [ 'required' ],
        },
        labels: {
          user_quantity: 'Количество пользователей',
          role_id: 'Группа',
        },
        isLoading: false,
        shown: false
      }
    },
    computed: {
      computedForm() {
        const roles = this.$store.getters['ViewUser/getRoleList'].map(item => Object.assign(item, { value: String(item.id) }));
        const permissions = this.$store.getters['ViewUser/getPermissionList'];
        const checks = this.$store.getters['ViewUser/getCheckList'];

        return [
          { id: 1, type: 'number', name: 'user_quantity', label: 'Количество пользователей:', desc: '' },
          { id: 2, type: 'select', name: 'role_id', label: 'Группа:', desc: '', data: roles },
          {
            id: 3,
            type: 'toggle-list',
            name: 'permissions',
            label: 'Доступные полномочия:',
            desc: '',
            data: permissions
          },
          { id: 4, type: 'toggle-list-checkbox', name: 'checks', label: 'Доступные проверки:', desc: '', data: checks }
        ].filter(item => !(item.id === 4 && this.data.role_id !== '3'));
      }
    },
    methods: {
      create() {
        if (this.validate()) {
          this.isLoading = true;
          this.$store.dispatch('ViewUser/createUser', this.data).then((res) => {
            if (res.hasOwnProperty('errors')) {
              this.errors = res.errors;
            } else {
              this.errors = [];
              this.$emit('result', {
                text: 'Пользователи успешно созданы!',
                className: 'alert--success'
              });

              const a = document.createElement('a');
              a.href = res;
              a.click();

              this.$emit('close');
            }
            this.isLoading = false;
          });
        }
      },
    }
  }
</script>

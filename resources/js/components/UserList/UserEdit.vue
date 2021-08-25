<template>
  <popup @close="$emit('close')" :isLoading="isLoading" :shown="shown">
    <template v-slot:header>Редактирование пользователя</template>
    <template v-slot:body>
      <alert :messages="errors"></alert>
      <v-form :forms="computedForm" v-model="data"></v-form>
    </template>
    <template v-slot:footer>
      <button class="btn btn--primary ml-auto" @click="edit">Редактировать</button>
    </template>
  </popup>
</template>

<script>
  import formValidation from "@/mixins/formValidation";

  export default {
    mixins: [ formValidation ],
    props: {
      id: {
        type: Number,
        required: true,
      }
    },
    mounted() {
      setTimeout(() => this.shown = true);

      this.isLoading = true;
      this.$store.dispatch('ViewUser/updateUser', { id: this.id }).then((res) => {
        if (res.hasOwnProperty('errors')) {
          this.errors = res.errors;
        } else {

          const permissions = res.permissions.reduce((carry, item) => {
            return Object.assign({}, carry, {
              [item.name]: item.value
            });
          }, {});

          this.data = {
            login: res.login,
            password: '',
            username: res.name,
            role_id: String(res.role_id),
            permissions,
            checks: []
          }
        }
        this.isLoading = false;
      });
    },
    data() {
      return {
        data: {
          login: '',
          password: '',
          username: '',
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
      computedUser() {
        return this.$store.getters['ViewUser/getUser'];
      },
      computedForm() {
        const roles = this.$store.getters['ViewUser/getRoleList'].map(item => Object.assign(item, { value: String(item.id) }));
        const permissions = this.$store.getters['ViewUser/getPermissionList'];
        const checks = this.$store.getters['ViewUser/getCheckList'];

        return [
          { id: 1, type: 'text', name: 'login', label: 'Логин:' },
          { id: 2, type: 'password', name: 'password', label: 'Пароль:' },
          { id: 3, type: 'text', name: 'username', label: 'Юзернейм:' },
          { id: 4, type: 'select', name: 'role_id', label: 'Группа:', data: roles },
          { id: 5, type: 'toggle-list', name: 'permissions', label: 'Доступные полномочия:', data: permissions },
          { id: 6, type: 'toggle-list-checkbox', name: 'checks', label: 'Доступные проверки:', data: checks }
        ].filter(item => !(item.name === 'checks' && this.data.role_id !== '3'));
      }
    },
    methods: {
      edit() {
        if (this.validate()) {
          this.isLoading = true;
          this.$store.dispatch('ViewUser/editUser', Object.assign({}, this.data, { id: this.id })).then((res) => {
            if (res.hasOwnProperty('errors')) {
              this.errors = res.errors;
            } else {
              this.errors = [];
              this.$emit('result', {
                text: `Пользователь с <strong>ID: ${this.id}</strong> успешно измненен!`,
                className: 'alert--success'
              });

              this.$emit('close');
            }
            this.isLoading = false;
          });
        }
      },
    }
  }
</script>

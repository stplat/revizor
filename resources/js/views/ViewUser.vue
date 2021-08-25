<template>
  <view-layout :breadcrumbs="computedBreadcrumbs" :route-name="routeName">
    <template v-slot:title>Пользователи</template>
    <user-list></user-list>
  </view-layout>
</template>
<script>
  import ViewUser from '../stores/modules/ViewUser';

  export default {
    components: {},
    props: {
      initialData: {
        type: Object,
        required: true
      },
      routeName: {
        type: String,
        required: true
      }
    },
    data() {
      return {}
    },
    computed: {
      computedBreadcrumbs() {
        return [
          { name: 'Главная', slug: '/' },
          { name: 'Пользователи', slug: '' },
        ];
      }
    },
    created() {
      this.$store.registerModule('ViewUser', ViewUser);
      this.$store.commit('ViewUser/setUserList', this.initialData.userList);
      this.$store.commit('ViewUser/setRoleList', this.initialData.roleList);
      this.$store.commit('ViewUser/setPermissionList', this.initialData.permissionList);
      this.$store.commit('ViewUser/setCheckList', this.initialData.checkList);
    },
    beforeDestroy() {
      this.$store.unregisterModule('ViewUser');
    }
  }
</script>

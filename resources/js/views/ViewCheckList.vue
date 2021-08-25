<template>
  <view-layout :breadcrumbs="computedBreadcrumbs" :route-name="routeName">
    <template v-slot:title>Список проверок</template>
    <check-list></check-list>
  </view-layout>
</template>
<script>
  import ViewCheckList from '../stores/modules/ViewCheckList';

  export default {
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
          { name: 'Список проверок' },
        ];
      }
    },
    created() {
      this.$store.registerModule('ViewCheckList', ViewCheckList);
      this.$store.commit('ViewCheckList/setCheckList', this.initialData.checkList);
      this.$store.commit('ViewCheckList/setAuthTypeList', this.initialData.authTypeList);
    },
    beforeDestroy() {
      this.$store.unregisterModule('ViewCheckList')
    }
  }
</script>

<template>
  <view-layout :breadcrumbs="computedBreadcrumbs" :route-name="routeName">
    <template v-slot:title>Панель управления</template>
    <div class="layout__container">
    </div>
  </view-layout>
</template>
<script>
  export default {
    props: {
      initialData: {
        type: Object,
        required: false
      },
      routeName: {
        type: String,
        required: true
      }
    },
    computed: {
      computedBreadcrumbs() {
        return [
          { name: 'Главная', slug: '/' },
          { name: 'Панель управления', slug: '' }
        ];
      }
    },
  }
</script>

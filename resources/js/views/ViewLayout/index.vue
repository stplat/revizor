<template>
  <main class="layout"
        :class="{'is-opened': shownAside}">
    <div class="layout__aside">
      <v-aside :route-name="routeName"></v-aside>
    </div>
    <div class="layout__content">
      <v-header v-model="shownAside" :breadcrumbs="breadcrumbs">
        <slot name="title"></slot>
        <template v-slot:tip><slot name="tip"></slot></template>
      </v-header>
      <slot></slot>
    </div>
  </main>
</template>
<script>
  import VAside from './Aside';
  import VHeader from './Header';

  export default {
    components: {
      VAside, VHeader
    },
    props: {
      breadcrumbs: {
        type: Array,
        required: true
      },
      routeName: {
        type: String,
        required: true
      }
    },
    data() {
      return {
        shownAside: false
      }
    }
  }
</script>

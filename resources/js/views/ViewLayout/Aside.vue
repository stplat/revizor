<template>
  <div class="aside">
    <div class="aside__logo">
      <a :href="asset('')"><img :src="asset('images/logo.png')" alt=""/></a>
    </div>
    <div class="aside__menu">
      <v-menu :route-name="routeName"></v-menu>
    </div>
  </div>
</template>
<script>
import VMenu from "./Menu";

export default {
  components: {
    VMenu
  },
  props: {
    routeName: {
      type: String,
      required: true
    }
  }
};
</script>

<template>
  <div class="header">
    <div class="header__top">
      <div class="header__btn">
        <hamburger v-model="computedToggleAside"></hamburger>
      </div>
      <div class="header__user">
        <user></user>
      </div>
    </div>
    <div class="header__bottom">
      <div class="header__left">
        <div class="header-title">
          <h1>
            <slot></slot>
          </h1>
          <slot name="tip"></slot>
        </div>
      </div>
      <div class="header__right">
        <ul class="breadcrumbs">
          <li class="breadcrumbs-item" v-for="(item, key) in breadcrumbs" :key="key">
            <a :href="item.slug" v-if="item.slug">{{ item.name }}</a>
            <template v-if="!item.slug">{{ item.name }}</template>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
  import User from './User';

  export default {
    components: {
      User
    },
    model: {
      prop: 'shownAside',
      event: 'toggleAside'
    },
    props: {
      shownAside: {
        type: Boolean,
        required: false
      },
      breadcrumbs: {
        type: Array,
        required: true
      }
    },
    data() {
      return {}
    },
    computed: {
      computedToggleAside: {
        get() {
          return this.shownAside;
        },
        set(value) {
          this.$emit('toggleAside', value);
        }
      }
    }
  }
</script>

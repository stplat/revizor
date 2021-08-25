<template>
  <div class="menu">
    <ul class="menu__list">
      <li
        v-for="(item, key) in computedMenuList"
        :key="'menu-link-' + key + '-' + _uid"
      >
        <template v-if="item.hasOwnProperty('title')">
          <div class="menu__title">{{ item.title }}</div>
        </template>
        <template
          v-else-if="!item.hasOwnProperty('subList') || !item.subList.length"
        >
          <a
            class="menu__link"
            :href="asset(item.slug)"
            :class="{
              ['menu__link--' + item.slug]: item.slug,
              'is-active': item.hasOwnProperty('active') && item.active
            }"
          >
            {{ item.name }}</a
          >
        </template>
        <template v-else>
          <input
            type="checkbox"
            hidden
            :id="'menu-link-' + key + '-' + _uid"
            :checked="
              item.subList.some(subItem => subItem.hasOwnProperty('active'))
            "
          />
          <label
            class="menu__link menu__link--label"
            :for="'menu-link-' + key + '-' + _uid"
            :class="'menu__link--' + item.slug"
            >{{ item.name }}</label
          >
          <ul class="menu__sub-list">
            <li
              v-for="(subItem, subKey) in item.subList"
              :key="'menu-sub-link-' + subKey + '-' + _uid"
            >
              <a
                class="menu__link menu__link--sub"
                :class="{
                  'is-active':
                    subItem.hasOwnProperty('active') && subItem.active
                }"
                :href="asset(subItem.slug)"
              >
                {{ subItem.name }}</a
              >
            </li>
          </ul>
        </template>
      </li>
    </ul>
  </div>
</template>
<script>
export default {
  props: {
    routeName: {
      type: String,
      required: true
    }
  },
  computed: {
    computedMenuList() {
      const routeName = this.routeName === "index" ? "" : this.routeName;
      return [
        { name: "Панель управления", slug: "" },
        { title: "Основное меню" },
        { name: "Проверки", slug: "checks" },
        { name: "Распознавания", slug: "recognitions" },
        { name: "Пользователи", slug: "users" }
      ].map(item => Object.assign(item, { active: item.slug === routeName }));
    }
  }
};
</script>

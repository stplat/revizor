<template>
  <div :class="computedClassName" :title="computedTitle"></div>
</template>
<script>
export default {
  props: {
    type: {
      type: String,
      default: "1",
      required: false,
      validator: value => {
        return ["1", "2", "3", "4", "5", "6", "7"].indexOf(value) !== -1;
      }
    }
  },
  computed: {
    computedClassName() {
      switch (this.type) {
        case "1":
          return "sign-flag";
        case "4":
          return "sign-flag sign-flag--box";
        case "5":
          return "sign-flag sign-flag--box sign-flag--red";
        case "6":
          return "sign-flag sign-flag--vote";
        case "7":
          return "sign-flag sign-flag--vote sign-flag--red";
      }
    },
    computedTitle() {
      return [
        { id: 1, name: "Не хватает видео" },
        { id: 2, name: "Сбой трансляции" },
        { id: 3, name: "Не хватает ящиков" },
        { id: 4, name: "Плохо видно ящики" },
        { id: 5, name: "Не видно ящики" },
        { id: 6, name: "Промежуточная явка не совпадает" },
        { id: 7, name: "Явка не совпадает" }
      ].reduce((carry, item) => {
        return String(item.id) === this.type ? item.name : carry;
      }, "");
    }
  }
};
</script>

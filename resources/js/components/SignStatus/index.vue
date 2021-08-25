<template>
  <div :class="computedClassName" :title="computedTitle"></div>
</template>
<script>
export default {
  props: {
    status: {
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
      switch (this.status) {
        case "2":
          return "sign sign--right";
        case "3":
          return "sign sign--clip";
        case "4":
          return "sign sign--plane";
        case "5":
          return "sign sign--print";
        case "6":
          return "sign sign--wrench";
        case "7":
          return "sign sign--heart";
        default:
          return "sign";
      }
    },
    computedTitle() {
      return [
        { id: 1, name: "Новое событие" },
        { id: 2, name: "Не нарушение" },
        { id: 3, name: "Сформирована жалоба" },
        { id: 4, name: "Жалоба отправлена" },
        { id: 5, name: "Ответ получен" },
        { id: 6, name: "Проблема решена" },
        { id: 7, name: "Проблема не решена" }
      ].reduce((carry, item) => {
        return String(item.id) === this.status ? item.name : carry;
      }, "");
    }
  }
};
</script>

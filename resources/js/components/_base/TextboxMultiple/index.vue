<template>
  <div class="multiple-textbox">
    <div class="multiple-textbox__input"
         v-for="(item, key) in list"
         :key="_uid + key"
    >
      <input class="form-control"
             :id="item.id"
             :name="name"
             :value="item.value"
             @input="input"
      >
      <button class="multiple-textbox__btn" title="Добавить ссылку"
              v-if="item.id === 1"
              @click.stop="addTextbox"></button>
      <button class="multiple-textbox__btn multiple-textbox__btn--close" title="Удалить ссылку"
              v-if="item.id !== 1"
              @click.stop="removeTextbox(item.id)"></button>
    </div>
  </div>
</template>
<script>
  export default {
    props: {
      value: {
        type: Array,
        required: true
      },
      name: {
        type: String,
        required: false
      }
    },
    data() {
      return {
        list: [
          { id: 1, value: '' }
        ]
      }
    },
    methods: {
      input(e) {
        this.list = this.list.map(item => {
          if (Number(item.id) === Number(e.target.id)) {
            item.value = e.target.value;
            return item;
          }

          return {
            id: item.id,
            value: item.value
          };
        });

        this.$emit('input', this.list.map(item => item.value).filter(item => item), e);
      },
      addTextbox() {
        const arrayLength = this.list.length;
        this.list.splice(arrayLength, 1, { id: arrayLength + 1, value: '' });
      },
      removeTextbox(id) {
        const index = this.list.findIndex(item => item.id === id);
        this.list.splice(index, 1);
        this.$emit('input', this.list.map(item => item.value).filter(item => item), null, this.name);
      }
    }
  }
</script>
<style lang="scss" scoped>
  .multiple-textbox {

  }
</style>

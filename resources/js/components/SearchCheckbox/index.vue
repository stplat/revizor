<template>
  <div class="search-checkbox">
    <div class="search-checkbox__input">
      <input type="text" class="form-control" v-model="search" @focus="opened = true">
    </div>
    <transition name="fade">
      <div class="search-checkbox__list" v-show="opened">
        <div class="filter-autocomplete">
          <div class="filter-autocomplete__all">
            <div class="filter-checkbox">
              <input type="checkbox" :id="_uid"
                     v-indeterminate="indeterminate"
                     :checked="allChecked"
                     @change="toggleAll">
              <label :for="_uid">Выбрать все</label>
            </div>
          </div>
          <ul class="filter-autocomplete__list">
            <li v-for="(item, key) in dataFound" :key="key">
              <div class="filter-checkbox"
                   :class="item.className">
                <input type="checkbox" :id="_uid + '_' + key" :value="item.id" v-model="checked">
                <label :for="_uid + '_' + key">
                  {{ item.name }} {{ item.hasOwnProperty('region_number') ? `(${item.region_number})` : '' }}</label>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </transition>
  </div>
</template>
<script>
  export default {
    props: {
      data: {
        type: Array,
        required: true
      },
      value: {
        type: Array
      }
    },
    data() {
      return {
        opened: false,
        indeterminate: false,
        allChecked: false,
        search: '',
      }
    },
    watch: {
      /* Отслеживаем состояние и навешивание/удаляем обработчик */
      opened(value) {
        if (value) {
          document.addEventListener('click', this.closeOnDocument);
        } else {
          document.removeEventListener('click', this.closeOnDocument);
        }
      },
      checked(value) {
        if (!value.length) {
          this.indeterminate = false;
          this.allChecked = false;
        } else if (value.length === this.data.length) {
          this.indeterminate = false;
          this.allChecked = true;
        } else {
          this.indeterminate = true;
          this.allChecked = false;
        }
      }
    },
    computed: {
      checked: {
        get() {
          return this.value;
        },
        set(value) {
          this.$emit('input', value);
        }
      },
      dataFound() {
        return this.data.filter(item => item.name.toLowerCase().indexOf(this.search.toLowerCase()) !== -1);
      }
    },
    methods: {
      toggleAll(e) {
        this.$emit('input', e.target.checked ? this.data.map(item => item.id) : []);
      },
      /* Прячем выпадашку при клике по документу */
      closeOnDocument(e) {
        if (this.$el !== e.target.closest('.search-checkbox')) {
          this.opened = false;
        }
      }
    }
  }
</script>
<style lang="scss" scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity .08s;
  }

  .fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */
  {
    opacity: 0;
  }
</style>

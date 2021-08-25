<template>
  <div class="filter">
    <div class="filter__head"
         :class="{'is-active': opened}"
         @click="opened = !opened"><h4>{{ title }}</h4></div>
    <transition name="fade">
      <div class="filter__body" v-show="opened">
        <div class="filter__search" v-if="type === 'search'">
          <input type="text" class="form-control" v-model="search">
        </div>
        <div class="filter__limit" v-if="type === 'limit'">
          <input type="text" id="from" class="form-control" placeholder="от"
                 v-prevent-number="true"
                 v-model="from">
          <input type="text" id="to" class="form-control" placeholder="до"
                 v-prevent-number="true"
                 v-model="to">
        </div>
        <div class="filter__autocomplete">
          <div class="filter-autocomplete" :class="{'without-all': withoutAll || type === 'radio'}">
            <div class="filter-autocomplete__all">
              <div class="filter-checkbox">
                <input type="checkbox" :id="_uid"
                       v-indeterminate="indeterminate"
                       :checked="allChecked"
                       @change="toggleAll">
                <label :for="_uid">Выбрать все</label>
              </div>
            </div>
            <ul class="filter-autocomplete__list" v-if="type !== 'radio'">
              <li v-for="(item, key) in dataFound" :key="key">
                <div class="filter-checkbox"
                :class="item.className">
                  <input type="checkbox" :id="_uid + '_' + key" :value="item.id" v-model="checked">
                  <label :for="_uid + '_' + key">
                    {{ item.name }} {{ item.hasOwnProperty('region_number') ? `(${item.region_number})` : '' }}</label>
                </div>
              </li>
            </ul>
            <ul class="filter-autocomplete__list" v-if="type === 'radio'">
              <li v-for="(item, key) in dataFound" :key="key">
                <div class="filter-radio">
                  <input type="radio" :name="_uid" :id="_uid + '_' + key" :value="item.id" v-model="checked">
                  <label :for="_uid + '_' + key"
                         :class="[
                         { 'filter-checkbox__label': item.hasOwnProperty('color') },
                         item.hasOwnProperty('color') ? `filter-checkbox__label--${item.color}` : ''
                         ]">
                    {{ item.name }} {{ item.hasOwnProperty('region_number') ? `(${item.region_number})` : '' }}</label>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="filter__reset"
             v-show="checked.length"
             @click="reset"><span>Сбросить</span></div>
      </div>
    </transition>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        opened: false,
        indeterminate: false,
        allChecked: false,
        search: '',
        from: '',
        to: ''
      }
    },
    props: {
      title: {
        type: String,
        required: true
      },
      data: {
        type: Array,
        required: true
      },
      type: {
        type: String,
        default: ''
      },
      withoutAll: {
        type: Boolean,
        default: false
      },
      value: {
        type: Array
      }
    },
    methods: {
      toggleAll(e) {
        this.$emit('input', e.target.checked ? this.data.map(item => item.id) : []);
      },
      reset() {
        this.$emit('input', []);
        this.from = this.to = '';
      }
    },
    watch: {
      from() {
        if (this.from || this.to) {
          this.$emit('input', [[this.from, this.to]]);
        } else if (!this.from && !this.to) {
          const value = this.value.slice(1);
          this.$emit('input', value);
        }
      },
      to() {
        if (this.from || this.to) {
          this.$emit('input', [[this.from, this.to]]);
        } else if (!this.from && !this.to) {
          const value = this.value.slice(1);
          this.$emit('input', value);
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
          this.from = this.to = '';
          this.$emit('input', value);
        }
      },
      dataFound() {
        return this.data.filter(item => item.name.toLowerCase().indexOf(this.search.toLowerCase()) !== -1);
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

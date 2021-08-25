<template>
  <section class="position-relative">
    <div class="tab-screen__body">
      <div class="row">
        <div class="col-md-12">
          <v-filter title="Найти участок"
                    type="search"
                    :data="computedUiks"
                    v-model="data.uiks"></v-filter>
          <v-filter title="Регионы"
                    type="search"
                    :data="computedRegions"
                    v-model="data.regions"></v-filter>
          <v-filter title="Явка"
                    type="limit"
                    :data="computedVotes"
                    v-model="data.votes"></v-filter>
          <v-filter title="Официальная явка"
                    type="limit"
                    :data="computedVotes"
                    v-model="data.votesOfficial"></v-filter>
          <v-filter title="Отклонение"
                    type="limit"
                    :data="computedVotesPercent"
                    v-model="data.votesPercent"></v-filter>
          <v-filter title="Тип ящика"
                    :data="computedBoxTypes"
                    v-model="data.boxTypes"></v-filter>
          <v-filter title="Обработка"
                    :data="computedProcesses"
                    v-model="data.processes"></v-filter>
          <v-filter title="События"
                    :data="computedViolationTypes"
                    v-model="data.violationTypes"></v-filter>
          <v-filter title="Реакция на события"
                    :data="computedViolationStatuses"
                    v-model="data.violationStatuses"></v-filter>
          <v-filter title="Другое"
                    :data="computedOthers"
                    v-model="data.others"></v-filter>
          <!--          <div class="check-total">Показано: {{ count }} из {{ count }}</div>-->
          <div class="check__buttons">
            <button class="btn btn--primary" @click="confirm">Применить</button>
            <button class="btn btn--secondary" @click="reset">Сбросить</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>

  export default {
    data() {
      return {
        data: {
          id: null,
          uiks: [],
          regions: [],
          votes: [],
          votesOfficial: [],
          votesPercent: [],
          boxTypes: [],
          processes: [],
          violationTypes: [],
          violationStatuses: [],
          others: []
        },
        errors: [],
      }
    },
    props: {},
    mounted() {
      this.data.id = this.$store.getters['ViewCheck/getCheck'].check_id;
    },
    methods: {
      reset() {
        this.data.regions = [];
      },
      confirm() {
        if (this.validate()) {

          this.$emit('loading', true);
          this.$store.dispatch('ViewCheck/updateUiks', this.data).then((res) => {
            if (!res.hasOwnProperty('errors')) {

            } else {
              this.errors = res.errors;
            }

            this.$emit('loading', false);
          });
        }
      },
      validate() {
        let { regions, uiks, boxTypes } = this.data;

        return !this.errors.length;
      }
    },
    computed: {
      computedUiks() {
        return this.$store.getters['ViewCheck/getUikList'];
      },
      computedRegions() {
        return this.$store.getters['ViewCheck/getRegionList'];
      },
      computedBoxTypes() {
        return this.$store.getters['ViewCheck/getBoxTypeList'];
      },
      computedVotes() {
        return [
          { id: [ 0, 100 ], name: 'Менее 100' },
          { id: [ 100, 250 ], name: '100 - 250' },
          { id: [ 250, 500 ], name: '250 - 500' },
          { id: [ 500, 1000 ], name: '500 - 1 000' },
          { id: [ 1000, 1500 ], name: '1 000 - 1 500' },
          { id: [ 1500, 2000 ], name: '1 500 - 2 000' },
          { id: [ 2000, 999999 ], name: '2 000 и более' }
        ];
      },
      computedVotesPercent() {
        return [
          { id: [ 0, 5 ], name: 'Менее 5%' },
          { id: [ 5, 10 ], name: '5 - 10%' },
          { id: [ 10, 15 ], name: '10 - 15%' },
          { id: [ 15, 20 ], name: '15 - 20%' },
          { id: [ 20, 30 ], name: '20 - 30%' },
          { id: [ 30, 40 ], name: '30 - 40%' },
          { id: [ 40, 50 ], name: '40 - 50%' },
          { id: [ 50, 100 ], name: '50% и более' }
        ];
      },
      computedProcesses() {
        return [
          { id: 1, name: 'В обработке', className: 'filter-checkbox--processing' },
          { id: 2, name: 'В черном списке', className: 'filter-checkbox--blacklist' },
          { id: 3, name: 'Исключен из анализа', className: 'filter-checkbox--excluded' },
          { id: 4, name: 'Обработан не сначала', className: 'filter-checkbox--not-first' },
          { id: 5, name: 'Была ошибка', className: 'filter-checkbox--error' },
        ];
      },
      computedViolationTypes() {
        return this.$store.getters['ViewCheck/getViolationTypeList'];
      },
      computedViolationStatuses() {
        return this.$store.getters['ViewCheck/getViolationStatusList'];
      },
      computedOthers() {
        return [
          { id: 1, name: 'Подходит для подсчета' },
          { id: 2, name: 'Не подходит для подсчета' },
          { id: 3, name: 'Не выбрана камера' },
          { id: 4, name: 'Посчитан вручную' }
        ];
      }
    }
  }
</script>

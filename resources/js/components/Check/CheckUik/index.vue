<template>
  <div class="uik">
    <preloader v-if="isLoading"></preloader>
    <div class="uik__tile">
      <ul class="uik-tile">
        <li class="uik-tile__item"
            v-for="(item, key) in computedTile"
            :class="item.class"
            :key="key">
          <div class="uik-tile__title">{{ item.title }}</div>
          <div class="uik-tile__text">{{ item.text }}</div>
          <div class="uik-tile__value">{{ item.value }}</div>
        </li>
      </ul>
    </div>
    <div class="uik__container">
      <div class="uik__filter">
        <uik-filter @loading="loading"></uik-filter>
      </div>
      <div class="uik__map">
        <uik-map></uik-map>
      </div>
    </div>
    <div class="uik__container">
      <div class="uik__table">
        <div class="uik__title">Список участков</div>
        <uik-table></uik-table>
      </div>
      <div class="uik__chart" v-if="uikChart">
        <div class="uik__title">Обработка видео</div>
        <uik-chart @empty="uikChart = false"></uik-chart>
      </div>
    </div>
  </div>
</template>
<script>
  import UikMap from './UikMap';
  import UikFilter from './UikFilter';
  import UikTable from './UikTable';
  import UikChart from './UikChart';

  export default {
    components: { UikMap, UikFilter, UikTable, UikChart },
    props: {},
    data() {
      return {
        uikChart: true,
        isLoading: false
      }
    },
    methods: {
      loading(bool) {
        this.isLoading = bool;
      }
    },
    computed: {
      computedTile() {
        const check = this.$store.getters['ViewCheck/getCheck'];

        return [
          {
            title: 'Участки',
            text: 'Количество загруженных участков в систему',
            value: check.uiks_count,
            class: ''
          },
          {
            title: 'Подходят',
            text: 'Участки, на которых камера подходит для подсчета явки',
            value: check.uik_camera_count,
            class: 'uik-tile__item--settings'
          },
          {
            title: 'Не подходят',
            text: 'Участки, на которых расположение ящиков не позволяет посчитать явку',
            value: check.uiks_count - check.uik_camera_count,
            class: 'uik-tile__item--negative'
          },
          {
            title: 'События',
            text: 'Общее количество выявленных событий',
            value: check.violation_count,
            class: 'uik-tile__item--event'
          },
          {
            title: 'Голосов',
            text: 'Количество распознанных системой действий как «голос»',
            value: `${check.revisor_votes} ± ${Math.round(check.revisor_votes * check.constant.revisor_error)}`,
            class: 'uik-tile__item--voice'
          },
          {
            title: 'Отклонение явки',
            text: 'Отклонение данных Ревизора от официальных',
            value: `${check.votes_deviation_count} ± ${Math.round(check.votes_deviation_count * check.constant.revisor_error)}`,
            class: 'uik-tile__item--offset'
          }
        ]
      }
    }
  }
</script>

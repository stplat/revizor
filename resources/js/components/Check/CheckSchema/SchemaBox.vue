<template>
  <div class="schema-box" :class="computedBoxClass">
    <div class="schema-box__tip">
      <tip button-type="question" button-font-size="16px" title="Поиск ящиков" :direction="tooltipDirection">
        описание описание описание описание описание описание описание описание описание описание описание
      </tip>
    </div>
    <div class="schema-box__title">{{ initialData.title}}</div>
    <div class="schema-box__text"
         v-for="(item, key) in initialData.text"
         :key="key"
         :data-key="item.name">{{ item.value }}
    </div>
    <div class="schema-box__progress" v-if="initialData.progress">
      <div class="progress">
        <div class="progress__bar"
             :class="computedProgressClass"
             role="progressbar"
             :style="{width: `${initialData.progress}%`}"
             aria-valuenow="10"
             aria-valuemin="0"
             aria-valuemax="100"></div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      initialData: {
        type: Object,
        required: true
      },
      color: {
        type: String,
        required: false
      },
      tooltipDirection: {
        type: String,
        required: false,
        default: 'left'
      }
    },
    computed: {
      /* Класс для карточки */
      computedBoxClass() {
        if (this.color === 'green') return 'schema-box--green';
      },
      /* Класс для прогрес-бара */
      computedProgressClass() {
        if (this.color === 'green') return 'progress__bar--success';
        return 'progress__bar--warning';
      }
    }
  }
</script>

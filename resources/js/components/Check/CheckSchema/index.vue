<template>
  <div class="schema">
    <div class="schema__row">
      <schema-box :initial-data="computedSearchBox"></schema-box>
    </div>
    <div class="schema__row">
      <div class="schema__col">
        <schema-box :initial-data="computedChoiceCam" color="green"></schema-box>
        <div class="schema__ico schema__ico--arrow"></div>
      </div>
      <div class="schema__col">
        <div class="schema__ico"></div>
      </div>
      <div class="schema__col">
        <div class="schema__ico schema__ico--arrow schema__ico--arrow-left"></div>
        <schema-box :initial-data="computedAnalytics" tooltip-direction="right"></schema-box>
      </div>
    </div>
    <div class="schema__row">
      <schema-box :initial-data="computedCounting"></schema-box>
    </div>
  </div>
</template>
<script>
  import SchemaBox from './SchemaBox';

  export default {
    components: {
      SchemaBox
    },
    data() {
      return {}
    },
    computed: {
      /* Поиск ящиков */
      computedSearchBox() {
        const check = this.$store.getters['ViewCheck/getCheck'];

        return {
          title: 'Поиск ящиков:',
          text: [
            {
              name: 'Обработано видео:',
              value: `${check.videos_processing_box_true_count} из ${check.videos_processing_box_count}`
            },
            {
              name: 'Проверено распознаваний:',
              value: `${check.videos_with_recognition_true_count} из ${check.videos_with_recognition_count}`
            }
          ],
          progress: Math.round(Number(check.videos_with_recognition_true_count) / Number(check.videos_with_recognition_count) * 100)
        }
      },
      /* Выбор камеры */
      computedChoiceCam() {
        const check = this.$store.getters['ViewCheck/getCheck'];

        return {
          title: 'Выбор камеры:',
          text: [
            {
              name: 'Участков обработано:',
              value: `${check.uik_videos_processed_count} из ${check.uiks_count}`
            },
            {
              name: 'Проверено:',
              value: `${check.uik_camera_count} из ${check.uiks_count}`
            }
          ],
          progress: Math.round(Number(check.uik_camera_count) / Number(check.uiks_count) * 100)
        }
      },
      /* Подсчет явки */
      computedCounting() {
        const check = this.$store.getters['ViewCheck/getCheck'];

        return {
          title: 'Подсчет явки:',
          text: [
            {
              name: 'Обработано видео:',
              value: `${check.videos_processing_counting_true_count} из ${check.videos_processing_counting_count}`
            },
            {
              name: 'В обработке:',
              value: `${check.videos_processing_counting_false_count} видео с ${check.uik_videos_processing_counting_false_count} участка (-ов) `
            }
          ],
          progress: Math.round(Number(check.videos_processing_counting_true_count) / Number(check.videos_processing_counting_count) * 100)
        }
      },
      /* Анализ результатов */
      computedAnalytics() {
        const check = this.$store.getters['ViewCheck/getCheck'];

        return {
          title: 'Анализ результатов:',
          text: [
            {
              name: 'Участков с отклонением явки:',
              value: `${check.uik_violation_count}`
            },
            {
              name: 'Участков исключено:',
              value: `${check.camera_count_with_summary}`
            }
          ]
        }
      }
    },
    mounted() {
    }
  }
</script>

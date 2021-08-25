<template>
  <div class="in-marker">
    <div class="in-marker__head">
      <div class="in-marker__title">
        Регион {{ marker.region }}, УИК {{ marker.uik_number }}
      </div>
      <div class="in-marker__sign">
        <ul class="in-marker-sign" v-if="marker.violation_types.length">
          <li v-for="(item, key) in marker.violation_types" :key="key">
            <!-- <sign-flag :violationType="{ id: item.id, name: item.name }"></sign-flag> -->
          </li>
        </ul>
      </div>
    </div>
    <div class="in-marker__body">
      <div class="in-marker__left">
        <ul class="in-marker-list">
          <li class="in-marker-list__item" data-key="Событий">
            {{ marker.violations_count }}
          </li>
          <li class="in-marker-list__item" data-key="Избирателей">
            {{ marker.voters_registered }}
          </li>
          <li class="in-marker-list__item" data-key="Явка">
            {{ marker.votes_cameras_main_true_count }} ±
            {{ marker.votes_cameras_main_true_count_with_revisor_error }}
          </li>
        </ul>
        <div class="in-marker-box">
          <div class="in-marker-box__head">Основное</div>
          <div class="in-marker-box__row" data-key="Подходит для подсчёта:">
            <template v-if="marker.votes_cameras_main_true_count">
              да
            </template>
            <template
              v-else-if="
                !marker.votes_cameras_main_true_count &&
                  !marker.votes_cameras_main_null_count &&
                  marker.votes_cameras_main_false_count
              "
            >
              нет
            </template>
            <template v-else-if="marker.votes_cameras_main_null_count">
              не выбрана камера
            </template>
          </div>
          <div class="in-marker-box__row" data-key="Официальная явка:">
            {{
              marker.ballots_in_ballot_boxes_after_election
                ? marker.ballots_in_ballot_boxes_after_election
                : "н/д"
            }}
          </div>
          <div class="in-marker-box__row" data-key="Отклонение:">
            <template
              v-if="
                marker.deviation &&
                  marker.deviation_error &&
                  marker.official_turnout_percent
              "
            >
              <tip-overflow :text="computedTipDeviation">{{
                computedDeviation
              }}</tip-overflow>
            </template>
            <template v-else>н/д</template>
          </div>
        </div>
        <div class="in-marker-box" v-if="marker.votes_cameras_main_true_count">
          <div class="in-marker-box__head">Ошибки</div>
          <div
            class="in-marker-box__row"
            data-key="Участок исключен из анализа:"
          >
            {{ marker.cameras_count && marker.blacklist_count ? "да" : "нет" }}
          </div>
          <div class="in-marker-box__row" data-key="Причина:">
            <template v-if="marker.blacklist_reason">
              {{ marker.blacklist_reason }}
            </template>
            <template v-else-if="marker.cameras_count">
              Ошибка Ревизора превышает заявленный показатель в
              {{ 100 * marker.constant_revisor_error }}%
            </template>
          </div>
        </div>
        <div class="in-marker-box" v-if="marker.votes_cameras_main_true_count">
          <div class="in-marker-box__head">Обработка</div>
        </div>
      </div>
      <div class="in-marker__right">
        <ul class="in-marker-image">
          <li
            class="in-marker-image__item"
            v-for="(item, key) in marker.images"
            :key="key"
          >
            <div
              class="in-marker-image__head"
              :class="{ 'in-marker-image__head--right': item.main }"
            >
              Камера {{ item.cam_numeric_id }}
            </div>
            <img :src="item.raw_image" alt="" />
          </li>
        </ul>
      </div>
    </div>
    <div class="in-marker__footer">
      <button class="btn btn--primary">Детали</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    marker: {
      type: Object,
      required: true
    }
  },
  computed: {
    /* Строка для отклонения: */
    computedDeviation() {
      const marker = this.marker;
      return (
        marker.deviation +
        " ± " +
        marker.deviation_error +
        ", " +
        marker.official_turnout_percent
      );
    },
    /* Строка для всплывающей подсказки отклонения: */
    computedTipDeviation() {
      const marker = this.marker;

      const x = marker.deviation - marker.deviation_error,
        n = (Math.abs(x) / marker.votes_official_count) * 100,
        y = marker.deviation + marker.deviation_error,
        k = (Math.abs(y) / marker.votes_official_count) * 100;

      return `От ${x} (${n}%) до ${y} (${k}%) избирателей'`;
    }
  },
  mounted() {}
};
</script>

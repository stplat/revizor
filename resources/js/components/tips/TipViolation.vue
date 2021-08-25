<template>
  <tip :title="computedViolationTypeName" :isLoading="isLoading">
    <template v-slot:btn>{{ computedViolationTypeName }}</template>
    <div class="tip-dropdown__title mb-3">О событии</div>
    <ul class="tip-dropdown__list">
      <li data-key="Идентификатор(ы):">
        {{ Array.isArray(violation) ? violation.join(", ") : violation }}
      </li>
      <template v-if="issetDate && violationType !== 7">
        <li data-key="Дата:">{{ date }}</li>
        <li data-key="Время:" v-if="time">{{ time }}</li>
      </template>
      <template v-if="violationType === 7">
        <li data-key="Официальная явка:">
          {{ official }}, {{ parseInt((official / reg) * 100) }}
        </li>
        <li data-key="Явка от ревизора:">
          <tip-overflow :text="computedRevisorVotes">
            {{ turnout }} ± {{ parseInt(turnout * constant.revisor_error) }},
            {{ parseInt((official / reg) * 100) }}
          </tip-overflow>
        </li>
        <li data-key="Отклонение:">
          <tip-overflow :text="computedDeviation">
            {{ deviation }} ±
            {{ parseInt(Math.abs(deviation) * constant.revisor_error) }},
            {{ parseInt((Math.abs(deviation) / official) * 100) }}
          </tip-overflow>
        </li>
        <li data-key="Обработан Ревизором без ошибок:">
          {{ skipVotesSummary | boolean }}
        </li>
      </template>
      <template v-if="violationType === 4">
        <li data-key="Коэф. размера ящиков:">
          {{ coefficient }}
        </li>
      </template>
      <template v-if="violationType === 5">
        <li data-key="Ящиков необходимо:">{{ computedNeededBoxes }}</li>
        <li data-key="Ящиков в поле зрении камеры:">3</li>
      </template>
    </ul>
    <div class="tip-dropdown__title mt-4 mb-3">Ручной подсчет явки</div>
    <ul class="tip-dropdown__list">
      <li data-key="Отправлен на ручной подсчет:">да</li>
      <li data-key="Реальная явка:">341</li>
      <li data-key="Отклонение от реальной:">623</li>
    </ul>
  </tip>
</template>
<script>
import Tip from "@/stores/modules/Tip";

export default {
  created() {
    if (!this.$store.hasModule("Tip")) {
      this.$store.registerModule("Tip", Tip);
    }
  },
  props: {
    violation: {
      type: [String, Array],
      required: true
    },
    uik: {
      type: String,
      required: false,
      default: null
    },
    title: {
      type: String,
      required: false
    }
  },
  data() {
    return {
      issetDate: true,
      date: null,
      time: null,
      coefficient: "",
      errors: [],
      isLoading: true,
      violationType: null,
      official: null,
      reg: null,
      turnout: null,
      deviation: null,
      constant: {},
      skipVotesSummary: false
    };
  },
  mounted() {
    const violation = Array.isArray(this.violation)
      ? this.violation[0]
      : this.violation;

    this.$store
      .dispatch("Tip/showViolation", { violation, uik: this.uik })
      .then(res => {
        if (res.hasOwnProperty("errors")) {
          this.errors = res.errors;
        } else {
          this.errors = [];
          this.isLoading = false;
          this.violationType = res.violation.type;
          this.constant = res.violation.constant;
          this.coefficient = `${parseInt(
            res.violation.normalized_width_k * 100
          )}% (меньше ${res.violation.constant.boxes_width_threshold * 100}%)`;

          let { date_start, date_end } = res.violation;

          if ((!date_start && !date_end) || !date_start)
            return (this.issetDate = false);

          if ((date_start && !date_end) || date_start === date_end) {
            this.date = this.formatDateHelper(date_start);
            this.time = this.formatTimeHelper(date_start);
          } else if (date_start && date_end) {
            const dateStart = this.formatDateHelper(date_start);
            const dateEnd = this.formatDateHelper(date_end);
            const timeStart = this.formatTimeHelper(date_start);
            const timeEnd = this.formatTimeHelper(date_end);

            if (dateStart === dateEnd) {
              this.date = dateStart;
              this.time = `с ${timeStart} по ${timeEnd}`;
            } else {
              this.date = `с ${dateStart} ${timeStart} по ${dateEnd} ${timeEnd}`;
            }
          }

          if (res.votes) {
            this.official = res.votes.ballots_in_ballot_boxes_after_election;
            this.reg = res.votes.voters_registered;
            this.turnout = res.votes.voters_turnout;
            this.deviation = res.votes.deviation;
            this.skipVotesSummary = res.votes.skip_votes_summary;
          }
        }
      });
  },
  computed: {
    computedDeviation() {
      const error = parseInt(
        Math.abs(this.deviation) * this.constant.revisor_error
      );

      const x = parseInt(this.deviation - error),
        n = parseInt((Math.abs(x) / this.official) * 100),
        y = parseInt(this.deviation + error),
        k = parseInt((Math.abs(y) / this.official) * 100);

      return `От ${x} (${n}%) до ${y} (${k}%) избирателей`;
    },
    computedRevisorVotes() {
      const error = parseInt(this.turnout * this.constant.revisor_error);

      const x = this.turnout - error,
        n = parseInt((Math.abs(x) / this.reg) * 100),
        y = this.turnout + error,
        k = parseInt((Math.abs(y) / this.reg) * 100);

      return `От ${x} (${n}%) до ${y} (${k}%) избирателей`;
    },
    computedViolationTypeName() {
      return [
        { id: 1, name: "Не хватает видео" },
        { id: 2, name: "Сбой трансляции" },
        { id: 3, name: "Не хватает ящиков" },
        { id: 4, name: "Плохо видно ящики" },
        { id: 5, name: "Не видно ящики" },
        { id: 6, name: "Промежуточная явка не совпадает" },
        { id: 7, name: "Явка не совпадает" }
      ].reduce((carry, item) => {
        return item.id === this.violationType ? item.name : carry;
      }, "");
    },
    computedNeededBoxes() {
      const uik = this.$store.getters["Tip/getUik"];
      const violation = this.$store.getters["Tip/getViolation"];

      if (
        violation.hasOwnProperty("constant") &&
        uik.hasOwnProperty("voters_registered")
      ) {
        const constant = violation.constant;

        if (uik.voters_registered <= constant.one_box_voters_threshold) {
          return "1";
        }

        if (
          constant.one_box_voters_threshold <
          uik.voters_registered <=
          constant.two_box_voters_threshold
        ) {
          return "2";
        }

        if (uik.voters_registered >= constant.three_box_voters_threshold) {
          return "3+";
        }
      }

      return "н/д";
    }
  },
  filters: {
    boolean(value) {
      return value ? "нет" : "да";
    }
  }
};
</script>

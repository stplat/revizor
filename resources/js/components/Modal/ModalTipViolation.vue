<template>
  <tip :title="computedViolationTypeName">
    <template v-slot:btn>{{ computedViolationTypeName }}</template>
    <div class="tip-dropdown__title mb-3" style="width: 300px">О событии</div>
    <ul class="tip-dropdown__list">
      <li data-key="Идентификатор(ы):">{{ computedViolationId }}</li>
      <template v-if="issetDate && computedViolationType !== 7">
        <li data-key="Дата:">{{ date }}</li>
        <li data-key="Время:" v-if="time">{{ time }}</li>
      </template>
      <template v-if="computedViolationType === 7">
        <li data-key="Официальная явка:">
          {{ official }}, {{ parseInt((official / reg) * 100) }}%
        </li>
        <li data-key="Явка от ревизора:">
          <tip-overflow :text="computedRevisorVotes" :with-border="true">
            {{ turnout }} ± {{ parseInt(turnout * constant.revisor_error) }},
            {{ parseInt((official / reg) * 100) }}%
          </tip-overflow>
        </li>
        <li data-key="Отклонение:">
          <tip-overflow :text="computedDeviation" :with-border="true">
            {{ parseInt(deviation) }} ±
            {{ parseInt(Math.abs(deviation) * constant.revisor_error) }},
            {{ parseInt((Math.abs(deviation) / official) * 100) }}%
          </tip-overflow>
        </li>
        <li data-key="Обработан Ревизором без ошибок:">
          {{ skipVotesSummary | boolean }}
        </li>
      </template>
      <template v-if="computedViolationType === 6">
        <li data-key="Официальная явка:">
          {{ official }}, {{ parseInt((official / reg) * 100) }}%
        </li>
        <li data-key="Явка от ревизора:">
          <tip-overflow :text="computedRevisorVotes" :with-border="true">
            {{ turnout }} ± {{ parseInt(turnout * constant.revisor_error) }},
            {{ parseInt((official / reg) * 100) }}%
          </tip-overflow>
        </li>
        <li data-key="Отклонение:">
          <tip-overflow :text="computedDeviation" :with-border="true">
            {{ parseInt(deviation) }} ±
            {{ parseInt(Math.abs(deviation) * constant.revisor_error) }},
            {{ parseInt((Math.abs(deviation) / official) * 100) }}%
          </tip-overflow>
        </li>
        <li data-key="Обработан Ревизором без ошибок:">
          {{ skipVotesSummary | boolean }}
        </li>
      </template>
      <template v-if="computedViolationType === 4">
        <li data-key="Коэф. размера ящиков:">
          {{ computedCoefficient }}
        </li>
      </template>
      <template v-if="computedViolationType === 5">
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
export default {
  created() {},
  props: {
    /* Только для вывода в виде текста */
    violation: {
      type: [Array, String],
      required: true
    }
  },
  data() {
    return {
      constant: {},
      date: null,
      deviation: null,
      errors: [],
      issetDate: true,
      official: null,
      reg: null,
      skipVotesSummary: false,
      time: null,
      turnout: null
    };
  },
  created() {
    let { date_start, date_end } = this.$store.getters["Modal/getViolation"];

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

    const constant = this.$store.getters["Modal/getConstant"];

    const votes =
      this.computedViolationType === 7
        ? this.$store.getters["Modal/getOfficialVotes"]
        : this.$store.getters["Modal/getIntermediateOfficialVotes"];

    if (votes) {
      this.official =
        votes.ballots_in_ballot_boxes_after_election ??
        votes.voters_voted_in_ballot_box;
      this.reg = votes.voters_registered;
      this.turnout = votes.voters_turnout;
      this.deviation = votes.deviation;
      this.skipVotesSummary = votes.skip_votes_summary;
      this.constant = constant;
    }
  },
  computed: {
    computedViolationId() {
      return Array.isArray(this.violation)
        ? this.violation.join(", ")
        : this.violation;
    },
    computedViolationTypeName() {
      return this.$store.getters["Modal/getViolation"].type_name;
    },
    computedViolationType() {
      return this.$store.getters["Modal/getViolation"].type;
    },
    computedCoefficient() {
      const violation = this.$store.getters["Modal/getViolation"];

      return `${parseInt(violation.normalized_width_k * 100)}% (меньше ${this
        .constant.boxes_width_threshold * 100}%)`;
    },

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

    computedNeededBoxes() {
      const uik = this.$store.getters["Modal/getUik"];
      const violation = this.$store.getters["Modal/getViolation"];

      if (
        violation.hasOwnProperty("constant") &&
        uik.hasOwnProperty("voters_registered")
      ) {
        const constant = this.constant;

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

<template>
  <div>
    <div class="card card--small">
      <div class="card__header">
        <h3>Явка на участке от Ревизора</h3>
      </div>
      <div class="card__body">
        <template v-if="computedViolationType !== 6">
          <div class="pill" v-for="(item, key) in computedDays" :key="key">
            <input
              type="radio"
              :id="'pill-' + key"
              hidden
              :value="item"
              v-model="day"
            />
            <label :for="'pill-' + key">{{ item }} </label>
          </div>
        </template>
        <chart-line
          class="my-3"
          :initial-data="computedLineChart"
          :key="day"
        ></chart-line>
        <div class="mt-2">
          <span style="vertical-align:top; font-weight: bold"
            >Голосов за весь день:</span
          >
          <tip-overflow :text="computedVotesByDay" :with-border="true">
            {{ votesCount }} ± {{ error }}
          </tip-overflow>
        </div>
      </div>
    </div>
    <div class="card card--small pb-3">
      <div class="card__header">
        <h3>Уверенность ревизора в голосах</h3>
      </div>
      <chart-wheel
        class="mt-3"
        :initial-data="computedWheelChart"
      ></chart-wheel>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      isPressed: false,
      isLoading: true,
      day: "",
      votesCount: null,
      error: null
    };
  },
  computed: {
    computedViolationType() {
      return this.$store.getters["Modal/getViolation"].type;
    },
    computedVotesByDay() {
      const constant = this.$store.getters["Modal/getConstant"];

      if (this.computedViolationType === 6) {
        const interOfficialVotes = this.$store.getters[
          "Modal/getIntermediateOfficialVotes"
        ];
        this.day = interOfficialVotes.hasOwnProperty("violation_datetime_start")
          ? interOfficialVotes.violation_datetime_start
          : "";
      }

      const votes = (this.votesCount = this.$store.getters[
        "Modal/getRevisorVotes"
      ]
        .filter(item => item.day === this.day)
        .reduce((carry, item) => carry + item.times.length, 0));

      const error = (this.error = parseInt(votes * constant.revisor_error));

      const x = votes - error,
        y = votes + error;

      return `От ${x} до ${y} избирателей`;
    },
    computedDays() {
      const days = this.$store.getters["Modal/getRevisorVotes"].map(
        item => item.day
      );

      this.day = days.length ? days[0] : "";
      return days;
    },
    computedWheelChart() {
      const vote_conf = this.$store.getters["Modal/getRevisorVotes"].reduce(
        (carry, item) => {
          return carry.hasOwnProperty("conf10")
            ? {
                conf10: carry.conf10 + item.vote_conf.conf10,
                conf20: carry.conf20 + item.vote_conf.conf20,
                conf30: carry.conf30 + item.vote_conf.conf30,
                conf40: carry.conf40 + item.vote_conf.conf40,
                conf50: carry.conf50 + item.vote_conf.conf50,
                conf60: carry.conf60 + item.vote_conf.conf60,
                conf70: carry.conf70 + item.vote_conf.conf70,
                conf80: carry.conf80 + item.vote_conf.conf80,
                conf90: carry.conf90 + item.vote_conf.conf90,
                conf100: carry.conf100 + item.vote_conf.conf100
              }
            : item.vote_conf;
        },
        {}
      );

      const labels = [],
        data = [];

      for (let label in vote_conf) {
        labels.push(label);
        data.push(vote_conf[label]);
      }

      const chartdata = {
        labels: labels.map(item => {
          switch (item) {
            case "conf10":
              return "0-10%";
            case "conf20":
              return "10-20%";
            case "conf30":
              return "20-30%";
            case "conf40":
              return "30-40%";
            case "conf50":
              return "40-50%";
            case "conf60":
              return "50-60%";
            case "conf70":
              return "60-70%";
            case "conf80":
              return "70-80%";
            case "conf90":
              return "80-90%";
            case "conf100":
              return "90-100%";
          }
        }),
        datasets: [
          {
            label: "My First Dataset",
            data: data,
            backgroundColor: [
              "rgb(255, 99, 132)",
              "rgb(54, 162, 235)",
              "rgb(255, 205, 86)"
            ],
            hoverOffset: 4
          }
        ]
      };

      const options = {};

      return { chartdata, options };
    },
    computedLineChart() {
      const votes = this.$store.getters["Modal/getRevisorVotes"].filter(
        item => item.day === this.day
      );

      const realVotes = this.$store.getters["Modal/getRealVotes"].filter(
        item => item.day === this.day
      );

      const times = votes.length ? votes[0].times : [];
      const realTimes = realVotes.length ? realVotes[0].times : [];

      const chartdata = {
        labels: times.map(item => item.time),
        datasets: [
          {
            data: times.map(item => item.votes),
            fill: false,
            borderColor: "rgb(75, 192, 192)",
            label: "Ревизор"
          },
          {
            data: realTimes.map(item => item.votes),
            fill: false,
            borderColor: "rgb(255, 99, 132)",
            label: "Реальные голоса"
          }
        ]
      };

      const options = {
        scales: {
          y: {
            stacked: false
          }
        }
      };

      return { chartdata, options };
    }
  }
};
</script>

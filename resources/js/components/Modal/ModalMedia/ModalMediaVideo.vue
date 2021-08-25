<template>
  <div>
    <preloader v-if="isLoading"></preloader>
    <ul class="list mb-3">
      <li class="list__item list__item--key" data-key="Камера:">
        <input-select
          size="slim"
          v-model="data.camera"
          :data="computedCameraList"
          @change="changeCamera"
        ></input-select>
      </li>
    </ul>
    <ul class="list list--flex">
      <li class="list__item list__item--key" data-key="Дата:">
        <input-select
          size="slim"
          v-model="data.date"
          :data="computedDateList"
        ></input-select>
      </li>
      <li class="list__item list__item--key" data-key="Время:">
        <input-select
          size="slim"
          v-model="data.time"
          :data="computedTimeList"
          @change="changeTime"
        ></input-select>
      </li>
    </ul>
    <form
      method="post"
      action="https://peresmotr.group/player"
      style="width:800px;"
      target="iframe"
      ref="form"
      hidden
    >
      <input type="hidden" name="p1" />
      <input type="hidden" name="p2" />
      <input type="hidden" name="file" />
      <input type="hidden" name="report" />
    </form>
    <iframe
      ref="iframe"
      class="mt-3"
      frameborder="0"
      name="iframe"
      style="width:100%;height:515px;border:1px solid lightgrey;overflow:hidden;"
    ></iframe>
  </div>
</template>
<script>
export default {
  data() {
    return {
      data: {
        camera: "",
        date: "",
        time: ""
      },
      isLoading: false
    };
  },
  computed: {
    computedCameraList() {
      return this.$store.getters["Modal/getCameraList"].map(item => ({
        value: String(item.id),
        name: `Камера ${item.number}`
      }));
    },
    computedDateList() {
      return this.$store.getters["Modal/getCameraList"]
        .reduce((carry, item) => {
          return this.data.camera === String(item.id)
            ? item.dates ?? []
            : carry;
        }, [])
        .map(item => {
          return {
            value: String(item.date),
            name: String(item.date)
          };
        });
    },
    computedTimeList() {
      return this.$store.getters["Modal/getCameraList"]
        .reduce((carry, item) => {
          return this.data.camera === String(item.id)
            ? item.dates ?? []
            : carry;
        }, [])
        .reduce((carry, item) => {
          return this.data.date === String(item.date) ? item.times : carry;
        }, [])
        .map(item => {
          return {
            value: String(item),
            name: String(item)
          };
        });
    }
  },
  methods: {
    changeCamera() {
      this.data.date = "";
      this.data.time = "";
    },
    changeTime() {
      this.isLoading = true;
      let { camera, date, time } = this.data;
      time = time.split(" - ");

      const dateFrom = `${date} ${time[0]}`,
        dateTo = `${date} ${time[1]}`;

      this.$store
        .dispatch("Modal/showVideo", { camera, dateFrom, dateTo })
        .then(res => {
          if (res.hasOwnProperty("errors")) {
            this.errors = res.errors;
          } else {
            this.errors = [];
            this.$refs.form[0].value = res.p1;
            this.$refs.form[1].value = res.p2;
            this.$refs.form[2].value = res.file;
            this.$refs.form[3].value = "[]";
            this.$refs.form.submit();

            this.$refs.iframe.addEventListener("load", () => {
              this.isLoading = false;
            });
          }
        });
    }
  },
  mounted() {
    const violation = this.$store.getters["Modal/getViolation"];

    this.data.camera = this.$store.getters["Modal/getCameraList"].reduce(
      (carry, item) => {
        return item.violation === violation.id ? String(item.id) : carry;
      },
      ""
    );
  }
};
</script>

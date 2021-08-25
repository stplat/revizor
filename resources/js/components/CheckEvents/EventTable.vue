<template>
  <div>
    <v-client-table
      :data="table.data"
      :columns="table.columns"
      :options="table.options"
    >
      <template v-slot:indicator="props">
        <div
          class="indicator"
          :class="{ 'indicator--red': props.row.indicator }"
          :title="props.row.indicator ? 'Недоступно' : 'Доступно'"
        ></div>
      </template>
      <template v-slot:key="props">
        <ul class="uik-type-list">
          <li class="uik-type-list__item">
            {{
              Array.isArray(props.row.key)
                ? props.row.key.join(", ")
                : props.row.key
            }}
          </li>
        </ul>
      </template>
      <template v-slot:camera="props">
        {{ props.row.camera.number }}
      </template>
      <template v-slot:uik="props">
        {{ props.row.uik.number }}
      </template>
      <template v-slot:type="props">
        <ul class="uik-type-list">
          <li class="uik-type-list__item">
            <sign-type :type="props.row.type"></sign-type>
          </li>
        </ul>
      </template>
      <template v-slot:status="props">
        <ul class="uik-type-list">
          <li class="uik-type-list__item">
            <sign-status
              :status="props.row.status"
              v-if="props.row.status"
            ></sign-status>
          </li>
        </ul>
      </template>
      <template v-slot:actions="props">
        <div class="vuetable__icons">
          <a
            href=""
            @click.prevent="clickModal(props.row)"
            title="Информация о событии"
          >
            <unicon name="presentation-check" fill="royalblue" />
          </a>
        </div>
      </template>
    </v-client-table>
    <modal
      :props="{ check, camera, uik, violation }"
      :shown="shown"
      @close="shown = false"
    ></modal>
  </div>
</template>

<script>
export default {
  data() {
    return {
      check: "",
      violation: "",
      uik: "",
      camera: "",
      shown: false
    };
  },
  props: {},
  mounted() {
    const check = String(this.$store.getters["ViewCheck/getCheck"].check_id);

    setInterval(() => {
      this.$store.dispatch("ViewCheck/showViolations", { check });
    }, 30000);
  },
  methods: {
    clickModal(props) {
      this.check = String(this.$store.getters["ViewCheck/getCheck"].check_id);
      this.violation = Array.isArray(props.key)
        ? props.key.map(item => String(item)).sort((a, b) => b - a)[0]
        : String(props.key);
      this.uik = String(props.uik.id);
      this.camera = String(props.camera.id);
      this.shown = true;
    }
  },
  computed: {
    table() {
      const headings = {
        key: "#",
        date: "Дата",
        time: "Время",
        region: "Регион",
        uik: "Участок",
        camera: "Камера",
        type: "Вид",
        status: "Статус",
        actions: "Детали",
        indicator: ""
      };
      const violation = this.$store.getters[
        "ViewCheck/getViolationsForEventTable"
      ];

      const data = violation.map((item, i) => {
        let date, startDate, endDate, time, startTime, endTime;

        if (Array.isArray(item.id)) {
          date = item.date_start.sort((a, b) => new Date(a) - new Date(b));
          startDate = this.formatDateHelper(date[0]);
          endDate = this.formatDateHelper(date[date.length - 1]);
          startTime = this.formatTimeHelper(date[0]);
          endTime = this.formatTimeHelper(date[date.length - 1]);

          if (startDate === endDate) {
            date = startDate;
            time = startTime;
          } else {
            date = `с ${startDate} по ${endDate}`;
            time = `с ${startTime} по ${endTime}`;
          }

          if (startTime === endTime) {
            time = startTime;
          } else {
            time = `с ${startTime} по ${endTime}`;
          }
        } else {
          date = this.formatDateHelper(item.date_start);
          time = this.formatTimeHelper(item.date_start);
        }

        return {
          key: Array.isArray(item.id) ? item.id.sort()[0] : item.id,
          indicator: item.blocked,
          date: date,
          time: time,
          region: item.region_number,
          uik: { id: item.uik_id, number: item.uik_number },
          camera: { id: item.cam_numeric_id, number: item.cam_num },
          type: String(item.violation_type_id),
          status: item.status_id ? String(item.status_id) : false,
          actions: item
        };
      });

      /* Алгоритм поиска */
      // const filterAlgorithm = {
      //   region(row, query) {
      //     const cam_numerics = row.actions.cameras.reduce(
      //       (carry, item) => carry + item.cam_numeric_id,
      //       ""
      //     );
      //     const camIds = row.actions.cameras.reduce(
      //       (carry, item) => carry + item.cam_id,
      //       ""
      //     );

      //     return (
      //       row.actions.region +
      //       " " +
      //       row.actions.tik +
      //       " " +
      //       cam_numerics +
      //       " " +
      //       camIds
      //     ).includes(query);
      //   }
      // };

      return {
        data,
        options: {
          headings,
          _data: data,
          // filterAlgorithm,
          sortByColumn: true,
          sortable: ["key", "date"]
        },
        columns: Object.keys(headings)
      };
    }
  }
};
</script>

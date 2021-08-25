<template>
  <v-client-table
    :data="computedTable.data"
    :columns="computedTable.columns"
    :options="computedTable.options"
  >
    <template v-slot:events="props">
      <ul class="uik-type-list">
        <li
          class="uik-type-list__item"
          v-for="(type, key) in props.row.events"
          :key="key"
        >
          <!-- <sign-flag :violation-type="type"></sign-flag> -->
        </li>
      </ul>
    </template>
    <template v-slot:address="props">
      <tip-overflow :key="props.row.address">{{
        props.row.address
      }}</tip-overflow>
    </template>
    <template v-slot:actions="props">
      <div class="vuecomputedTable__icons">
        <a href="" @click.prevent="" title="Информация об участке">
          <unicon name="presentation-check" fill="royalblue" />
        </a>
        <a
          href=""
          @click.prevent="scrollToMap(props.row.actions.latlngs)"
          title="Найти на карте"
        >
          <unicon name="map-marker" fill="royalblue" />
        </a>
      </div>
    </template>
  </v-client-table>
</template>

<script>
export default {
  data() {
    return {};
  },
  props: {},
  mounted() {},
  methods: {
    scrollToMap(latlngs) {
      document.querySelector(".uik").scrollIntoView({
        behavior: "smooth",
        block: "start"
      });

      const map = this.$store.getters["ViewCheck/getUikMap"];

      map.setZoom(15);
      setTimeout(() => map.panTo(latlngs), 500);
    }
  },
  computed: {
    computedTable() {
      const headings = {
        key: "#",
        region: "Регион",
        uik: "Участок",
        events: "Событие",
        address: "Адрес",
        actions: "Детали"
      };

      const data = this.$store.getters["ViewCheck/getUiks"].map((item, i) => ({
        key: item.uik_id,
        region: item.region_number,
        uik: item.uik_number,
        events: item.violation_types.reduce((carry, type) => {
          const isType_1 = carry.some(el => el.id === 1);
          if (type.id === 1 && isType_1) return carry;

          carry.push(type);
          return carry;
        }, []),
        address: item.address,
        actions: item
      }));

      /* Алгоритм поиска */
      const filterAlgorithm = {
        region(row, query) {
          const cam_numerics = row.actions.cameras.reduce(
            (carry, item) => carry + item.cam_numeric_id,
            ""
          );
          const camIds = row.actions.cameras.reduce(
            (carry, item) => carry + item.cam_id,
            ""
          );
          const violationTypes = row.actions.violation_types.reduce(
            (carry, item) => carry + item.name,
            ""
          );

          return (
            row.actions.region +
            " " +
            row.actions.tik +
            " " +
            cam_numerics +
            " " +
            camIds +
            " " +
            violationTypes
          ).includes(query);
        }
      };

      return {
        data,
        options: {
          headings,
          _data: data,
          filterAlgorithm,
          sortByColumn: true,
          sortable: ["key"]
        },
        columns: Object.keys(headings)
      };
    }
  }
};
</script>

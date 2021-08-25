<template>
  <l-map id="leaflet" :zoom="zoom" :center="center" ref="leafletMap">
    <l-tile-layer :url="url" :attribution="attribution"/>
    <l-marker-cluster :options="markerClusters">
      <l-marker v-for="(marker, index) in markers" :key="index" :lat-lng="marker.latlngs" :icon="marker.icon"
                :options="{_violations: marker.violations}"
                @add="init($event, index)">
        <l-popup v-if="popup" :options="{ minWidth: 600 }">
          <slot name="popup" :marker="marker"/>
        </l-popup>
      </l-marker>
    </l-marker-cluster>
    <l-polyline v-for="(polyline, index) in polylines" :key="index" :lat-lngs="polyline" color="green"/>
  </l-map>
</template>

<script>
  export default {
    data() {
      return {
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
      }
    },
    props: {
      center: {
        type: Array,
        default: () => [ 66.25, 94.15 ]
      },
      zoom: {
        type: Number,
        default: 3
      },
      popup: {
        type: Boolean,
        required: false,
        default: false
      },
      markers: {
        type: Array,
        required: false,
        default: null
      },
      markerPopups: {
        type: Boolean,
        required: false,
        default: false
      },
      polylines: {
        type: Array,
        required: false,
        default: null
      },
      init: {
        type: Function,
        default: async function (e, index) {
          // await this.$nextTick(() => e.target.off('click'));
          // e.target.on('click', this.handlerClick.bind(null, e, index));
          // e.target.on('mouseover', () => e.target.openPopup());
          // e.target.on('mouseout', () => e.target.closePopup());
        }
      },
      handlerClick: {
        type: Function,
        default: function (e, index) {
          // console.log(index)
        }
      },
      markerClusters: {
        type: Object,
        required: false,
        default: null
      },
      options: {
        type: Object,
        required: false,
        default: null
      }
    },
    computed: {},
    methods: {},
    mounted() {
      /* Автоподбор ширины карты при изменени ширины окна */
      const togglers = document.querySelectorAll('.header__btn');

      [ ...togglers ].forEach((toggle => {
        toggle.addEventListener('click', () => {
          setTimeout(() => {
            this.$refs.leafletMap !== undefined ? this.$refs.leafletMap.mapObject.invalidateSize(true) : '';
          }, 200);
        });
      }));

      this.$emit('init', this.$refs.leafletMap.mapObject);
    }
  };
</script>

<style lang="scss">


</style>

<template>
  <div class="leaflet-map">
    <leaflet-map
      :markers="markers"
      :marker-clusters="markerClusters"
      :popup="true"
      @init="initMap">
      <template v-slot:popup="props">
        <uik-map-marker :marker="props.marker"></uik-map-marker>
      </template>
    </leaflet-map>
  </div>
</template>
<script>
  import UikMapMarker from './UikMapMarker';

  export default {
    components: { UikMapMarker },
    computed: {
      markers() {
        return this.$store.getters['ViewCheck/getUiks'].map(item => {
          return Object.assign(item, {
            latlngs: [ item.latitude, item.longitude ],
            icon: this.drawIcon(item.violations_count ? 'red' : 'green', item.violations_count ?? 0),
            violations: item.violations_count ?? 0,
            violation_types: item.violation_types.reduce((carry, type) => {
              const isType_1 = carry.some((el) => el.id === 1);
              if (type.id === 1 && isType_1) return carry;

              carry.push(type);
              return carry;
            }, [])
          });
        });
      },
      markerClusters() {
        const iconCreateFunction = (cluster) => {
          const childCount = function recursion(cluster) {
            let violations = 0;

            cluster._childClusters.forEach(item => violations += recursion(item));
            if (!cluster._markers.length) return violations;
            cluster._markers.forEach(item => {
              if (item.options._violations) {
                violations += parseInt(item.options._violations);
              }
            });

            return parseInt(violations);
          }(cluster);

          var c = ' marker-cluster-';
          if (childCount < 10) {
            c += 'small';
          } else if (childCount < 100) {
            c += 'medium';
          } else {
            c += 'large';
          }

          return new L.DivIcon({
            html: '<div><span>' + childCount + '</span></div>',
            className: 'marker-cluster ' + c,
            iconSize: new L.Point(40, 40)
          });
        };

        return { iconCreateFunction };
      }
    },
    methods: {
      initMap(map) {
        this.$store.commit('ViewCheck/setUikMap', map);
      },
      drawIcon(className, count) {
        return new L.DivIcon({
          className: 'asd',
          html: `<span class="leaflet-icon-mark leaflet-icon-mark--${className}">${count}</span>`
        });
      },
    }
  }
</script>
<style lang="scss" scoped>
  .leaflet-map {
    height: 600px;
  }
</style>

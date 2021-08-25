<template>
  <card size="small">
    <template v-slot:title>Подтверждение события</template>
    <template v-slot:title-right>
      <tip title="Заголовок подсказки"></tip>
    </template>
    <ul class="list">
      <li class="list__item list__item--key" data-key="Данные:">
        <input-select
          size="slim"
          v-model="media"
          :data="computedMedia"
        ></input-select>
      </li>
    </ul>
    <media-image v-show="media === '1'"></media-image>
    <media-video v-show="media === '2'"></media-video>
  </card>
</template>
<script>
export default {
  components: {
    mediaImage: require("./ModalMediaImage").default,
    mediaVideo: require("./ModalMediaVideo").default
  },
  props: {},
  data() {
    return {
      media: "1"
    };
  },
  computed: {
    computedMedia() {
      return [
        { value: "1", name: "Фото" },
        { value: "2", name: "Видео" }
      ];
    }
  },

  mounted() {}
};
</script>

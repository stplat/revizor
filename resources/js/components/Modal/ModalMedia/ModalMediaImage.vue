<template>
  <div>
    <ul class="list">
      <li
        class="list__item list__item--key"
        data-key="Отображать распознавания ящиков:"
      >
        <input-toggle v-model="recognition"></input-toggle>
      </li>
    </ul>
    <div class="gallery">
      <div class="gallery__flex">
        <div
          class="gallery__col"
          v-for="(cam, keyCam) in computedImagesByCamera"
          :key="keyCam"
        >
          <div class="gallery__title" :class="{ 'is-active': cam.countable }">
            Камера #{{ cam.number }}
          </div>
          <ul class="gallery__list">
            <li>
              <swiper :options="swiperOptions">
                <div
                  class="swiper-slide"
                  v-for="(image, keyImage) in cam.images"
                  :key="keyCam + keyImage"
                >
                  <div
                    class="gallery__list-item"
                    :data-date="image.date"
                    @click="clickImage(image)"
                  >
                    <img
                      :src="image.path"
                      :class="{ 'is-active': image.className }"
                    />
                  </div>
                </div>
              </swiper>
            </li>
          </ul>
        </div>
      </div>
      <div class="gallery__info">
        <modal-media-tip :image="activeImage"></modal-media-tip>
      </div>
      <div class="gallery__image" v-if="activeImage.path">
        <image-editor
          :controls="false"
          :disabled="true"
          :backgroundImagePath="activeImage.path"
          v-model="computedBoxList"
          :key="activeImage.id"
        ></image-editor>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  components: {
    ModalMediaTip: require("./ModalMediaTip").default
  },
  data() {
    return {
      recognition: true,
      activeImage: {
        id: null,
        path: "",
        date: "",
        boxes: [],
        height: "",
        width: ""
      },
      swiperOptions: {
        spaceBetween: 5,
        slidesPerView: 2,
        breakpoints: {
          993: {
            spaceBetween: 5,
            slidesPerView: 2
          },
          769: {
            spaceBetween: -50,
            slidesOffsetAfter: -60,
            slidesPerView: 1
          },
          500: {
            spaceBetween: -50,
            slidesOffsetAfter: -60,
            slidesPerView: 1
          },
          0: {
            spaceBetween: -50,
            slidesOffsetAfter: -60,
            slidesPerView: 1
          }
        }
      }
    };
  },
  computed: {
    computedImagesByCamera() {
      const cameras = this.$store.getters["Modal/getImages"];
      let activeImageId = this.activeImage.id;

      if (!this.activeImage.id && cameras.length && cameras[0].images.length) {
        this.activeImage = Object.assign({}, cameras[0].images[0]);
        activeImageId = cameras[0].images[0].id;
      }

      return cameras.map(item => {
        return Object.assign({}, item, {
          images: item.images.map(image => {
            return Object.assign({}, image, {
              className: image.id === activeImageId
            });
          })
        });
      });
    },
    computedBoxList() {
      if (this.recognition) {
        return this.activeImage.boxes.length ? this.activeImage.boxes : [];
      } else {
        return [];
      }
    }
  },
  methods: {
    clickImage(image) {
      this.activeImage = Object.assign({}, image);
    },
    parseJsonCoordForSquare(json, imageSize, type = "bbox") {
      let { width, height } = imageSize;

      const object = JSON.parse(json);
      let coord, x1, x2, y1, y2;

      if (type === "bbox") {
        (x1 = parseInt(Number(object.x1) * Number(width))),
          (y1 = parseInt(Number(object.y1) * Number(height))),
          (x2 = parseInt(Number(object.x2) * Number(width))),
          (y2 = parseInt(Number(object.y2) * Number(height)));
      } else {
        (x1 = parseInt(Number(object.top_left.x) * Number(width))),
          (y1 = parseInt(Number(object.top_left.y) * Number(height))),
          (x2 = parseInt(Number(object.bottom_right.x) * Number(width))),
          (y2 = parseInt(Number(object.bottom_left.x) * Number(height)));
      }

      return [
        { x: x1, y: y1 },
        { x: x2, y: y1 },
        { x: x2, y: y2 },
        { x: x1, y: y2 },
        { x: x1, y: y1 }
      ];
    }
  }
};
</script>

<template>
  <div v-if="computedUikList.length" style="position: relative">
    <preloader v-if="computedIsLoading"></preloader>
    <transition name="list" mode="out-in">
      <card :key="uikIndex" style="min-height: 720px">
        <template v-slot:title>
          <recognition-tip
            :uik="computedUik"
            v-if="computedUik.name"
          ></recognition-tip>
        </template>
        <div
          class="card__container"
          :class="{ 'mt-3': key > 0 }"
          v-for="(recognition, key) in computedRecognitionList"
          :key="'recognition-' + key"
        >
          <div
            class="card__col"
            v-for="(cam, key) in recognition.cameras"
            :key="'camera-' + key"
          >
            <recognition-editor
              :camera="cam"
              :uik="computedUik"
              :recognition="recognition"
              v-model="uikList"
              @updateDeletedBoxes="updateDeletedBoxes"
            ></recognition-editor>
          </div>
        </div>
      </card>
    </transition>
    <div class="paginate" v-if="uikList.length">
      <div class="paginate__list">
        <button
          class="paginate-btn"
          @click="clickPrevUik"
          v-if="uikIndex !== 0"
        >
          Назад
        </button>
        <button
          class="paginate-btn"
          @click="clickNextUik"
          v-if="uikIndex + 1 !== uikCount"
        >
          Далее
        </button>
        <button
          class="paginate-btn paginate-btn--green"
          @click="clickSendRecognitions"
          v-if="uikIndex + 1 === uikCount"
        >
          Отправить
        </button>
        <button
          class="paginate-btn paginate-btn--red"
          @click="clickExit"
          v-if="uikCount > 1 && uikIndex + 1 !== uikCount"
        >
          Выйти
        </button>
      </div>
      <div class="paginate__info">
        Проверено {{ uikIndex }} из {{ uikCount }} выгруженных участков
      </div>
    </div>
  </div>
</template>
<script>
export default {
  components: {
    RecognitionEditor: require("./RecognitionOrderSelectCamEditor").default,
    RecognitionTip: require("../RecognitionTip").default
  },
  props: {
    params: {
      type: Object, // параметры запроса
      required: true
    }
  },
  data() {
    return {
      uikList: [],
      uikIndex: 0,
      uikCount: null,
      data: {
        deletedBoxes: []
      },
      isLoading: false
    };
  },
  created() {},
  methods: {
    /**
     * Обновляем список удаленных ящиков
     * @param int boxId
     * @return void
     */
    updateDeletedBoxes(boxId) {
      if (!String(boxId).includes("temp")) {
        this.$set(this.data.deletedBoxes, this.data.deletedBoxes.length, boxId);
      }
    },
    /**
     * Предыдущий Участок
     * @return void
     */
    clickPrevUik() {
      this.uikIndex -= 1;
    },
    /**
     * Следующий Участок
     * @return void
     */
    clickNextUik() {
      this.uikIndex += 1;
    },
    /**
     * Выходим без сохранения изменений
     * @param boolean current
     * @return void
     */
    clickExit() {
      this.$store.dispatch("ViewRecognition/updateCheckingRecognition");
    },
    /**
     * Отправляем изменения по участкам
     * @param boolean current
     * @return void
     */
    clickSendRecognitions(current = false) {
      this.$store.commit("ViewRecognition/setIsLoading", true);
      let { deletedBoxes } = this.data;

      const boxes = [];
      let recognitions = [];

      this.uikList.forEach((uik, key) => {
        if ((current && key <= this.uikIndex) || !current) {
          uik.recognitions.forEach(recognition => {
            recognition.cameras.forEach(camera => {
              const objectRecognition = this.deepCloneHelper(recognition);
              objectRecognition.uik_id = uik.uik_id;
              objectRecognition.id = this.deepCloneHelper(
                camera
              ).recognition_id;
              recognitions.push(objectRecognition);

              camera.image.boxes.forEach(box => {
                box.recognition_id = camera.recognition_id;
                boxes.push(box);
              });
            });
          });
        }
      });

      recognitions = recognitions
        .map(recognition => {
          recognition.boxes = [];

          boxes.forEach(item => {
            if (item.recognition_id === recognition.id) {
              recognition.boxes.push(item);
            }
          });

          return recognition;
        })
        .map(recognition => {
          recognition.boxes_flag = !!recognition.boxes.length;
          recognition.boxes_num = recognition.boxes.length;
          recognition.countable = recognition.cameras.reduce((carry, item) => {
            return item.countable ? true : carry;
          }, false);
          recognition.cam_quality =
            recognition.boxes.reduce((carry, item) => {
              return (carry += item.box_quality);
            }, 0) / recognition.boxes.length;

          return recognition;
        });

      this.$store
        .dispatch("ViewRecognition/updateBox", {
          deletedBoxes,
          boxes,
          recognitions,
          params: this.params,
          current
        })
        .then(res => {
          if (res.hasOwnProperty("errors")) {
            this.errors = res.errors;
          } else {
            this.$emit("messages");
          }
          this.$store.commit("ViewRecognition/setIsLoading", false);
        });
    }
  },
  computed: {
    /**
     * Выбранные участки
     * @return boolean
     */
    computedUikList() {
      const uiks = this.$store.getters[
        "ViewRecognition/getUikListBySelectCamera"
      ];

      this.uikCount = uiks.length;
      this.uikIndex = 0;
      this.uikList = uiks;

      return uiks;
    },
    /**
     * Общий прелоадер для всех карточек у отчетов
     * @return boolean
     */
    computedIsLoading() {
      return this.$store.getters["ViewRecognition/getIsLoading"];
    },
    /**
     * Выбранный участок
     * @return array
     */
    computedUik() {
      return this.uikList[this.uikIndex];
    },
    /**
     * Список распознаваний участка
     * @return array
     */
    computedRecognitionList() {
      return this.computedUik.recognitions;
    }
  }
};
</script>

<style lang="scss" scoped>
.list-enter-active,
.list-leave-active {
  transition: transform 0.1s, opacity 0.05s;
}

.list-enter,
.list-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}

.list-enter.prev,
.list-leave-to.prev {
  opacity: 0;
  transform: translateX(30px);
}

.card__col {
  min-width: 700px;
}
</style>

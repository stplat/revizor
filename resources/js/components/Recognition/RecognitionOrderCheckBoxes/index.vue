<template>
  <div v-if="computedRecognitionList.length" style="position: relative">
    <preloader v-if="computedIsLoading"></preloader>
    <alert :messages="messages"></alert>
    <transition name="list" mode="out-in">
      <card :key="recognitionIndex" style="min-height: 720px">
        <template v-slot:title>
          <recognition-tip :uik="computedUik"></recognition-tip>
        </template>
        <recognition-editor
          :camera="computedCamera"
          :uik="computedUik"
          :recognition="computedRecognition"
          v-model="recognitionList"
          @updateDeletedBoxes="updateDeletedBoxes"
          @openPrevRecognition="clickShowPrevRecognition"
          @message="messages = ['Предыдущее распознавание отсутствует']"
        ></recognition-editor>
      </card>
    </transition>
    <div class="paginate" v-if="true">
      <div class="paginate__list">
        <button
          class="paginate-btn"
          @click="clickPrevUik"
          v-if="recognitionIndex !== 0"
        >
          Назад
        </button>
        <button
          class="paginate-btn"
          @click="clickNextUik"
          v-if="recognitionIndex + 1 !== recognitionCount"
        >
          Далее
        </button>
        <button
          class="paginate-btn paginate-btn--green"
          @click="clickSendRecognitions"
          v-if="recognitionIndex + 1 === recognitionCount"
        >
          Отправить
        </button>
        <button
          class="paginate-btn paginate-btn--red"
          @click="clickExit"
          v-if="
            recognitionCount > 1 && recognitionIndex + 1 !== recognitionCount
          "
        >
          Выйти
        </button>
      </div>
      <div class="paginate__info">
        Проверено {{ recognitionIndex }} из {{ recognitionCount }} выгруженных
        распознаваний
      </div>
    </div>
    <recognition-modal
      :shown="prevRecognition"
      :camera="prevRecognitionCamera"
      @close="prevRecognition = false"
    ></recognition-modal>
  </div>
</template>
<script>
export default {
  components: {
    RecognitionEditor: require("./RecognitionOrderCheckBoxesEditor").default,
    RecognitionModal: require("./RecognitionOrderCheckBoxesModal").default,
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
      recognitionList: [],
      recognitionIndex: 0,
      recognitionCount: null,
      data: {
        deletedBoxes: []
      },
      isLoading: false,
      prevRecognition: false,
      prevRecognitionCamera: {
        image: {
          boxes: []
        },
        boxTypes: []
      },
      messages: []
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
      if (!String(boxId).includes("temp") && !String(boxId).includes("clone")) {
        this.$set(this.data.deletedBoxes, this.data.deletedBoxes.length, boxId);
      }
    },
    /**
     * Предыдущий Участок
     * @return void
     */
    clickPrevUik() {
      this.recognitionIndex -= 1;
    },
    /**
     * Следующий Участок
     * @return void
     */
    clickNextUik() {
      this.recognitionIndex += 1;
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
     * Открываем предыдущее распознавания
     * @param boolean current
     * @return void
     */
    clickShowPrevRecognition() {
      this.$store.commit("ViewRecognition/setIsLoading", true);
      const recognition = this.computedRecognition.camera.recognition_id;
      const camera = this.computedRecognition.camera.id;

      this.$store
        .dispatch("ViewRecognition/showPrevRecognition", {
          recognition,
          camera
        })
        .then(res => {
          if (res.hasOwnProperty("errors")) {
            this.errors = res.errors;
          } else {
            if (res) {
              this.prevRecognition = true;
              this.prevRecognitionCamera = res;
            } else {
              this.messages = ["Предыдущее распознавание отсутствует"];
            }
          }
          this.$store.commit("ViewRecognition/setIsLoading", false);
        });
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

      this.recognitionList.forEach((recognition, key) => {
        if ((current && key <= this.recognitionIndex) || !current) {
          const objectRecognition = {};
          objectRecognition.uik_id = recognition.uik.uik_id;
          objectRecognition.id = recognition.camera.recognition_id;
          objectRecognition.boxes_flag = recognition.boxes_flag;
          objectRecognition.boxes_num = recognition.boxes_num;
          objectRecognition.cam_quality = recognition.cam_quality;
          objectRecognition.recognition_datetime =
            recognition.recognition_datetime;
          objectRecognition.cameras = [recognition.camera];
          recognitions.push(objectRecognition);

          recognition.camera.image.boxes.forEach(box => {
            box.recognition_id = recognition.camera.recognition_id;
            boxes.push(box);
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
          params: this.params
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
    computedRecognitionList() {
      const recognitions = this.$store.getters[
        "ViewRecognition/getRecognitionListByCheckBoxes"
      ];

      this.recognitionCount = recognitions.length;
      this.recognitionList = recognitions;
      this.recognitionIndex = 0;

      return recognitions;
    },
    /**
     * Общий прелоадер для всех карточек у отчетов
     * @return boolean
     */
    computedIsLoading() {
      return this.$store.getters["ViewRecognition/getIsLoading"];
    },
    /**
     * Выбранное распознование
     * @return array
     */
    computedRecognition() {
      return this.recognitionList[this.recognitionIndex];
    },
    /**
     * Выбранная камера
     * @return array
     */
    computedCamera() {
      return this.recognitionList[this.recognitionIndex].camera;
    },
    /**
     * Выбранный участок
     * @return array
     */
    computedUik() {
      return this.computedRecognition.uik;
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
</style>

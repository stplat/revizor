<template>
  <div>
    <div class="card__title">
      <h3>Камера #{{ camera.number }}</h3>
      <span>
        <button class="btn btn--primary" @click="$emit('openPrevRecognition')">
          Открыть предыдущее<br />
          распознавание с ящиками
        </button>
      </span>
    </div>
    <ul class="list">
      <li class="list__item list__item--key" data-key="Тип ящиков:">
        {{ camera.boxTypes | boxType }}
      </li>
      <li class="list__item list__item--key" data-key="Количество ящиков:">
        {{ camera.image.boxes.length }}
      </li>
      <li
        class="list__item list__item--key"
        data-key="Подставить ящики из предыдущего распознавания:"
      >
        <s-radio-toggle v-model="toggleBoxesPrevRecognition"></s-radio-toggle>
      </li>
    </ul>
    <image-editor
      :backgroundImagePath="camera.image.raw_image"
      v-model="computedBoxList"
    ></image-editor>
  </div>
</template>
<script>
export default {
  model: {
    prop: "modelValue",
    event: "update:modelValue"
  },
  props: {
    modelValue: {
      type: Array,
      required: false
    },
    camera: {
      type: Object,
      required: true
    },
    uik: {
      type: Object,
      required: true
    },
    recognition: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      oldBoxList: [],
      toggleBoxesPrevRecognition: false
    };
  },
  computed: {
    /**
     * Перегружаем ящики
     * @return array
     */
    computedBoxList: {
      get() {
        return this.camera.image.boxes;
      },
      set(v) {
        const recognitionList = this.deepCloneHelper(this.modelValue).map(
          item => {
            if (item.uik.uik_id !== this.uik.uik_id) return item;
            if (item.camera.id !== this.camera.id) return item;
            if (
              item.camera.recognition_id !==
              this.recognition.camera.recognition_id
            )
              return item;

            item.camera.boxTypes = v.reduce((carry, item) => {
              if (!carry.includes(item.type)) carry.push(item.type);
              return carry;
            }, []);
            item.camera.image.boxes.forEach(box => {
              if (v.map(item => item.box_id).indexOf(box.box_id) === -1) {
                this.$emit("updateDeletedBoxes", box.box_id);
              }
            });

            item.camera.image.boxes = v;

            return item;
          }
        );

        this.$emit("update:modelValue", recognitionList);
      }
    }
  },
  watch: {
    toggleBoxesPrevRecognition(v) {
      if (v) {
        this.$store.commit("ViewRecognition/setIsLoading", true);

        const recognition = this.camera.recognition_id;
        const camera = this.camera.id;

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
                this.oldBoxList = this.deepCloneHelper(this.computedBoxList);
                this.computedBoxList = res.image.boxes.map(item => {
                  return Object.assign({}, item, {
                    box_id: `clone-${item.box_id}`
                  });
                });
              } else {
                this.$emit("message");
              }
            }
            this.$store.commit("ViewRecognition/setIsLoading", false);
          });
      } else {
        this.computedBoxList = this.deepCloneHelper(this.oldBoxList);
      }
    }
  },
  filters: {
    boxType(value) {
      return value
        .map(item => {
          switch (item) {
            case "ballot_box":
              return "Избирательный ящик";
            case "koib":
              return "КОИБ";
            default:
              return "н/д";
          }
        })
        .join(" и ");
    }
  }
};
</script>

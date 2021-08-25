<template>
  <div>
    <div class="card__title">
      <h3>Камера #{{ camera.number }}</h3>
      <span>{{ recognition.date }}</span>
    </div>
    <ul class="list">
      <li class="list__item list__item--key" data-key="Подходит для подсчета:">
        <s-radio-toggle
          v-model="computedCountableCamera"
          :value="String(camera.id)"
        ></s-radio-toggle>
      </li>
      <li class="list__item list__item--key" data-key="Тип ящиков:">
        {{ camera.boxTypes | boxType }}
      </li>
      <li class="list__item list__item--key" data-key="Количество ящиков:">
        {{ camera.image.boxes.length }}
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
    return {};
  },
  computed: {
    /**
     * Переключатель "Подходит для подсчета" у камеры
     * @return array
     */
    computedCountableCamera: {
      get() {
        return this.camera.countable;
      },
      set(v) {
        const uikList = this.deepCloneHelper(this.modelValue).map(uik => {
          const uikObject = uik;

          uikObject.recognitions = uik.recognitions.map(recognition => {
            const recognitionObject = recognition;

            recognitionObject.cameras = recognition.cameras.map(camera => {
              const objectCamera = camera;

              if (uik.uik_id === this.uik.uik_id) {
                if (camera.id === this.camera.id) {
                  objectCamera.countable = v;
                } else {
                  objectCamera.countable = false;
                }
              }

              return objectCamera;
            });

            return recognitionObject;
          });

          return uikObject;
        });

        this.$emit("update:modelValue", uikList);
      }
    },
    /**
     * Перегружаем ящики
     * @return array
     */
    computedBoxList: {
      get() {
        return this.camera.image.boxes;
      },
      set(v) {
        const uikList = this.deepCloneHelper(this.modelValue).map(uik => {
          const uikObject = this.deepCloneHelper(uik);

          if (uik.uik_id !== this.uik.uik_id) return uikObject;

          uikObject.recognitions = uik.recognitions.map(recognition => {
            const recognitionObject = this.deepCloneHelper(recognition);

            if (recognition.date !== this.recognition.date)
              return recognitionObject;

            recognitionObject.cameras = recognition.cameras.map(camera => {
              const objectCamera = this.deepCloneHelper(camera);

              if (camera.id !== this.camera.id) return objectCamera;

              objectCamera.boxTypes = v.reduce((carry, item) => {
                if (!carry.includes(item.type)) carry.push(item.type);
                return carry;
              }, []);
              objectCamera.image.boxes.forEach(box => {
                if (v.map(item => item.box_id).indexOf(box.box_id) === -1) {
                  this.$emit("updateDeletedBoxes", box.box_id);
                }
              });

              objectCamera.image.boxes = v;

              return objectCamera;
            });

            return recognitionObject;
          });

          return uikObject;
        });

        this.$emit("update:modelValue", uikList);
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

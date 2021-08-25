<template>
  <div class="image-editor" ref="window">
    <div class="image-editor__control" v-if="controls && !disabled">
      <div class="image-editor-control">
        <div class="image-editor-tool">
          <input
            type="radio"
            hidden
            :id="'editor-btn-' + _uid"
            value="cursor"
            :name="'editor-btns-' + _uid"
            v-model="activeTool"
          />
          <label :for="'editor-btn-' + _uid" title="Выделить"></label>
        </div>
        <image-editor-select @change="addBox"></image-editor-select>
        <span class="image-editor-separate" />
        <button
          class="image-editor-btn image-editor-btn--delete"
          title="Удалить ящик"
          :disabled="!activeBoxId"
          @click="removeBox"
        ></button>
        <image-editor-select
          :data="computedBoxTypeList"
          v-model="computedBoxType"
          :disabled="!activeBoxId"
          button="box"
        ></image-editor-select>
      </div>
    </div>
    <div class="image-editor__canvas">
      <canvas ref="canvas"></canvas>
      <template
        v-if="
          isRendered && computedFirstPointActiveBoxCoord.hasOwnProperty('x')
        "
      >
      </template>
    </div>
  </div>
</template>
<script>
import canvas from "@/mixins/canvas";

export default {
  mixins: [canvas],
  components: {
    ImageEditorSelect: require("./ImageEditorSelect").default
  },
  model: {
    prop: "modelValue",
    event: "update:modelValue"
  },
  props: {
    modelValue: {
      type: Array,
      required: true
    },
    controls: {
      type: Boolean,
      required: false,
      default: true
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  data() {
    return {
      asd: "",
      activeTool: "cursor",
      activeBoxId: null,
      activeBox: {},
      dragged: {
        idBox: null,
        indexAngle: null,
        part: ""
      },
      data: {
        boxType: "1"
      }
    };
  },
  watch: {
    /* При изменении активного ящика */
    activeBoxId() {
      this.clearCanvas();
      this.init();
    },
    modelValue(v) {
      this.clearCanvas();
      this.init();
    }
  },
  computed: {
    computedBoxTypeList() {
      return [
        { id: 1, value: "koib", label: "КОИБ" },
        { id: 2, value: "ballot_box", label: "Избирательный ящик" },
        { id: 3, value: "n/d", label: "н/д" }
      ];
    },
    computedFirstPointActiveBoxCoord() {
      const activeBox = this.computedBoxList.filter(
        item => this.activeBoxId === item.id
      )[0];

      if (!activeBox) return {};

      let { x, y } = activeBox.cap.coord[0];
      return { x, y };
    },
    /**
     * Тип у ящика
     * @return void
     */
    computedBoxType: {
      get() {
        return this.modelValue.reduce((carry, item) => {
          return item.box_id === this.activeBoxId ? item.type : carry;
        }, "");
      },
      set(v) {
        const boxList = this.modelValue.map(item => {
          if (item.box_id === this.activeBoxId) {
            return Object.assign(item, { type: v });
          } else {
            return item;
          }
        });

        this.$emit("update:modelValue", boxList);
      }
    },

    /**
     * Ящики распознавания
     * @return array
     */
    computedBoxList() {
      return this.modelValue.map((item, key) => {
        let coordBox = [],
          coordCap = [];

        if (item.box_bbox_coords) {
          coordBox = this.parseJsonCoordForSquare(item.box_bbox_coords);
        }

        if (item.cap_rot_bbox || item.cap_ort_bbox) {
          coordCap =
            item.cap_type === "poly"
              ? this.parseJsonCoordForSquare(item.cap_rot_bbox, "poly")
              : this.parseJsonCoordForSquare(item.cap_ort_bbox);
        }

        return {
          id: item.box_id,
          type: item.type,
          cap: {
            settings: {
              colorBackground: "rgba(25, 255, 121, 0.5)",
              colorBorder: "rgba(25, 255, 121, 1)",
              points: {
                width: 8,
                height: 8,
                colorBackground: "rgba(25, 255, 121, 1)"
              }
            },
            coord: coordCap
          },
          box: {
            settings: {
              colorBackground: "rgba(255, 189, 217, 0.5)",
              colorBorder: "rgba(255, 189, 217, 1)",
              points: {
                width: 8,
                height: 8,
                colorBackground: "rgba(255, 189, 217, 1)"
              }
            },
            coord: coordBox
          }
        };
      });
    }
  },
  methods: {
    /* Инициируем отрисовку канвас и композиции */
    init() {
      if (this.backgroundImage.currentSrc) {
        this.drawImage(this.backgroundImage);
      }
      this.drawBoxes();
    },
    /* Подписываемся на событие нажатие мыши */
    onMouseDown() {
      if (this.computedBoxList.length) {
        this.indexOfBoxes();

        if (this.dragged.idBox) {
          this.activeBoxId = this.dragged.idBox;

          this.activeBox = this.deepCloneHelper(this.modelValue).reduce(
            (carry, item) => {
              return item.box_id === this.dragged.idBox ? item : carry;
            },
            {}
          );
        }
      }
    },
    /* Событие движения мыши */
    onMouseMove(e) {
      let { idBox, indexAngle, part } = this.dragged;

      if (this.isMouseDown && indexAngle !== -1) {
        const boxList = this.modelValue.map(item => {
          if (item.box_id === idBox) {
            if (part === "box") {
              const coord = this.changeJsonCoordPoint(item.box_bbox_coords);
              const json = JSON.parse(coord);

              item.box_bbox_coords = coord;
              item.normalized_width_k = Math.abs(json.x2 - json.x1);
              item.normalized_dist_k = this.getNormalizedCentroidSquare(json);
              item.centroid_k = JSON.stringify({
                x: (json.x1 + json.x2) / 2,
                y: (json.y1 + json.y2) / 2
              });
              item.box_quality =
                item.normalized_width_k * 0.6 +
                item.normalized_dist_k * 0.4 * item.normalized_width_k;
            } else {
              if (item.cap_type === "poly") {
                item.cap_rot_bbox = this.changeJsonCoordPoint(
                  item.cap_rot_bbox,
                  "poly"
                );

                const json = JSON.parse(item.cap_rot_bbox);
                let sumX = 0;
                let sumY = 0;

                for (let obj in json) {
                  sumX += json[obj].x;
                  sumY += json[obj].y;
                }

                item.cap_centroid_k = JSON.stringify({
                  x: sumX / 4,
                  y: sumY / 4
                });
              } else {
                item.cap_ort_bbox = this.changeJsonCoordPoint(
                  item.cap_ort_bbox
                );

                const json = JSON.parse(item.cap_ort_bbox);

                item.cap_centroid_k = JSON.stringify({
                  x: (json.x1 + json.x2) / 2,
                  y: (json.y1 + json.y2) / 2
                });
              }
            }
          }

          return item;
        });

        this.$emit("update:modelValue", boxList);
      } else if (this.isMouseDown && indexAngle === -1) {
        const boxList = this.modelValue.map(item => {
          if (item.box_id === idBox) {
            if (part === "box") {
              item.box_bbox_coords = this.changeJsonCoordPoly(
                this.activeBox.box_bbox_coords
              );

              const json = JSON.parse(item.box_bbox_coords);

              item.normalized_width_k = Math.abs(json.x2 - json.x1);
              item.normalized_dist_k = this.getNormalizedCentroidSquare(json);
              item.centroid_k = JSON.stringify({
                x: (json.x1 + json.x2) / 2,
                y: (json.y1 + json.y2) / 2
              });
              item.box_quality =
                item.normalized_width_k * 0.6 +
                item.normalized_dist_k * 0.4 * item.normalized_width_k;
            } else {
              if (item.cap_type === "poly") {
                item.cap_rot_bbox = this.changeJsonCoordPoly(
                  this.activeBox.cap_rot_bbox,
                  "poly"
                );

                const json = JSON.parse(item.cap_rot_bbox);
                let sumX = 0;
                let sumY = 0;

                for (let obj in json) {
                  sumX += json[obj].x;
                  sumY += json[obj].y;
                }

                item.cap_centroid_k = JSON.stringify({
                  x: sumX / 4,
                  y: sumY / 4
                });
              } else {
                item.cap_ort_bbox = this.changeJsonCoordPoly(
                  this.activeBox.cap_ort_bbox
                );

                const json = JSON.parse(item.cap_ort_bbox);

                item.cap_centroid_k = JSON.stringify({
                  x: (json.x1 + json.x2) / 2,
                  y: (json.y1 + json.y2) / 2
                });
              }
            }
          }

          return item;
        });

        this.$emit("update:modelValue", boxList);
      }
    },
    /* Подписываемся на событие отжатия мыши */
    onMouseUp() {
      this.dragged = { idBox: null, indexAngle: null, part: "" };
      this.activeBox = {};
    },
    /**
     * Получаем нормализованное значение удаления центров ящика и кадра
     * @param object coord
     * @return void
     */
    getNormalizedCentroidSquare(coord) {
      const distance = this.getDistanceBetweenPoints(
        {
          x: (coord.x1 + coord.x2) / 2,
          y: (coord.y1 + coord.y2) / 2
        },
        { x: 0.5, y: 0.5 }
      );

      return distance > 0.684931507 ? 0 : 1 - Math.pow(distance * 1.46, 2);
    },
    /**
     * Удаляем ящик
     * @return void
     */
    removeBox() {
      const boxList = this.deepCloneHelper(this.modelValue).filter(
        item => item.box_id !== this.activeBoxId
      );

      this.activeBoxId = null;
      this.$emit("update:modelValue", boxList);
    },
    /**
     * Создаем новый ящик
     * @return boolean
     */
    addBox(type) {
      const boxList = this.deepCloneHelper(this.modelValue);
      const center = { x: this.canvas.width / 2, y: this.canvas.height / 2 };
      let { width, height } = this.canvas;

      boxList.push({
        box_id: "temp-" + boxList.length + 1,
        box_bbox_coords: JSON.stringify({
          x1: (center.x - 40) / width,
          y1: (center.y + 5) / height,
          x2: (center.x + 40) / width,
          y2: (center.y + 80) / height
        }),
        // cap_ort_bbox: JSON.stringify({
        //   x1: (center.x - 40) / width,
        //   y1: (center.y - 40) / height,
        //   x2: (center.x + 40) / width,
        //   y2: (center.y + 0) / height
        // }),
        cap_rot_bbox: JSON.stringify({
          top_left: { x: (center.x - 40) / width, y: (center.y - 40) / height },
          top_right: {
            x: (center.x + 40) / width,
            y: (center.y - 40) / height
          },
          bottom_right: {
            x: (center.x + 40) / width,
            y: (center.y + 0) / height
          },
          bottom_left: {
            x: (center.x - 40) / width,
            y: (center.y + 0) / height
          }
        }),
        cap_type: "poly",
        box_quality: 0,
        conf: 1,
        type_conf: 1,
        normalized_width_k: 0,
        normalized_dist_k: 0,
        centroid_k: JSON.stringify({ x: 0, y: 0 }),
        cap_centroid_k: JSON.stringify({ x: 0, y: 0 }),
        type
      });

      this.$emit("update:modelValue", boxList);
    },
    /**
     * Получаем индекс ящика
     * @return boolean
     */
    indexOfBoxes() {
      let { xMouse, yMouse } = this.getMouseCoord();
      const point = { x: xMouse, y: yMouse };
      const activeBoxIndex = this.computedBoxList.findIndex(
        item => item.id === this.activeBoxId
      );
      const boxList = this.activeBoxId
        ? this.arraySwapHelper(this.computedBoxList, activeBoxIndex, 0)
        : this.computedBoxList;

      boxList.some(item => {
        let isBelongToPoly = false;

        ["cap", "box"].some(part => {
          const poly = item[part].coord;
          const res = this.isBelongToPoly(point, poly);
          isBelongToPoly = res.contain;

          if (isBelongToPoly) {
            this.dragged = Object.assign({}, this.dragged, {
              idBox: item.id,
              indexAngle: res.indexAngle,
              part
            });
          }

          return isBelongToPoly;
        });

        return isBelongToPoly;
      });
    },
    /**
     * Парсер координат из БД
     * @param json string
     * @param type string
     * @return array
     */
    parseJsonCoordForSquare(json, type = "bbox") {
      let { width, height } = this.canvas;

      const object = JSON.parse(json);
      let coord, x1, x2, y1, y2, x3, y3, x4, y4;

      if (type === "bbox") {
        (x1 = parseInt(Number(object.x1) * Number(width))),
          (y1 = parseInt(Number(object.y1) * Number(height))),
          (x2 = parseInt(Number(object.x2) * Number(width))),
          (y2 = parseInt(Number(object.y2) * Number(height)));

        return [
          { x: x1, y: y1 },
          { x: x2, y: y1 },
          { x: x2, y: y2 },
          { x: x1, y: y2 },
          { x: x1, y: y1 }
        ];
      } else {
        (x1 = parseInt(Number(object.top_left.x) * Number(width))),
          (y1 = parseInt(Number(object.top_left.y) * Number(height))),
          (x2 = parseInt(Number(object.top_right.x) * Number(width))),
          (y2 = parseInt(Number(object.top_right.y) * Number(height))),
          (x3 = parseInt(Number(object.bottom_right.x) * Number(width))),
          (y3 = parseInt(Number(object.bottom_right.y) * Number(height))),
          (x4 = parseInt(Number(object.bottom_left.x) * Number(width))),
          (y4 = parseInt(Number(object.bottom_left.y) * Number(height)));

        return [
          { x: x1, y: y1 },
          { x: x2, y: y2 },
          { x: x3, y: y3 },
          { x: x4, y: y4 },
          { x: x1, y: y1 }
        ];
      }
    },
    /**
     * Парсим из json в объект координаты ящика,
     * меняем положение точки под координаты мыши и парсим обратно в json
     * @param json string
     * @param type string
     * @return string (json)
     */
    changeJsonCoordPoint(json, type = "bbox") {
      let { indexAngle } = this.dragged;
      let { width, height } = this.canvas;
      const xMouse = this.xMouse;
      const yMouse = this.yMouse;

      const coord = JSON.parse(json);
      if (type === "bbox") {
        switch (indexAngle) {
          case 0:
            coord.x1 = xMouse / width;
            coord.y1 = yMouse / height;
            break;
          case 1:
            coord.x2 = xMouse / width;
            coord.y1 = yMouse / height;
            break;
          case 2:
            coord.x2 = xMouse / width;
            coord.y2 = yMouse / height;
            break;
          case 3:
            coord.x1 = xMouse / width;
            coord.y2 = yMouse / height;
            break;
        }
      } else {
        switch (indexAngle) {
          case 0:
            coord.top_left = {
              x: xMouse / width,
              y: yMouse / height
            };
            break;
          case 1:
            coord.top_right = {
              x: xMouse / width,
              y: yMouse / height
            };
            break;
          case 2:
            coord.bottom_right = {
              x: xMouse / width,
              y: yMouse / height
            };
            break;
          case 3:
            coord.bottom_left = {
              x: xMouse / width,
              y: yMouse / height
            };
            break;
        }
      }

      return JSON.stringify(coord);
    },
    /**
     * Парсим из json в объект координаты ящика,
     * меняем положение точки под координаты мыши и парсим обратно в json
     * @param json string
     * @param xMouse double
     * @param yMouse double
     * @param type string
     * @return string (json)
     */
    changeJsonCoordPoly(json, type = "bbox") {
      let { width, height } = this.canvas;
      const deltaXMouse = this.deltaXMouse / width;
      const deltaYMouse = this.deltaYMouse / height;
      const sourceCoord = JSON.parse(json);
      let coord = {};

      if (type === "bbox") {
        coord.x1 = sourceCoord.x1 + deltaXMouse;
        coord.y1 = sourceCoord.y1 + deltaYMouse;
        coord.x2 = sourceCoord.x2 + deltaXMouse;
        coord.y2 = sourceCoord.y2 + deltaYMouse;
      } else {
        coord = {
          top_left: {
            x: sourceCoord.top_left.x + deltaXMouse,
            y: sourceCoord.top_left.y + deltaYMouse
          },
          top_right: {
            x: sourceCoord.top_right.x + deltaXMouse,
            y: sourceCoord.top_right.y + deltaYMouse
          },
          bottom_right: {
            x: sourceCoord.bottom_right.x + deltaXMouse,
            y: sourceCoord.bottom_right.y + deltaYMouse
          },
          bottom_left: {
            x: sourceCoord.bottom_left.x + deltaXMouse,
            y: sourceCoord.bottom_left.y + deltaYMouse
          }
        };
      }

      return JSON.stringify(coord);
    },
    /**
     * Рисуем ящики с крышками
     * @return void
     */
    drawBoxes() {
      this.computedBoxList.forEach(item => {
        ["box", "cap"].forEach(part => {
          let poly = this.deepCloneHelper(item[part]);

          if (this.activeBoxId !== item.id) {
            poly.settings = Object.assign(poly.settings, {
              colorBackground: "transparent"
            });
          } else {
            poly.settings = Object.assign(poly.settings, {
              select: true
            });
          }

          this.drawPoly(poly);
        });
      });
    }
  }
};
</script>

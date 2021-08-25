export default {
  props: {
    backgroundImagePath: {
      type: String,
      required: false,
      default: 'public/img/1/uiks/74/123/1/raw_8uj28k9inmhbxyns.png'
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  data() {
    return {
      canvas: null,
      ctx: null,
      mouseEvent: {},
      isMouseDown: false,
      backgroundImage: {},
      isRendered: false,
      sourceXMouse: null,
      sourceYMouse: null,
      xMouse: null,
      yMouse: null,
      deltaXMouse: null,
      deltaXMouse: null
    }
  },
  watch: {
    /* При нажатии / отжатии клавиши мыши */
    isMouseDown(value) {
      if (value) {
        document.addEventListener('mousemove', this._onMouseMove);
        document.addEventListener('touchmove', this._onMouseMove);
        document.addEventListener('mouseup', this._onMouseUp);
        document.addEventListener('touchend', this._onMouseUp);
      } else {
        document.removeEventListener('mousemove', this._onMouseMove);
        document.removeEventListener('touchmove', this._onMouseMove);
        document.removeEventListener('mouseup', this._onMouseUp);
        document.removeEventListener('touchend', this._onMouseUp);
      }
    },
    backgroundImagePath() {
      this.onloadBackgroundImage().then(image => {
        this.canvas.width = image.naturalWidth;
        this.canvas.height = image.naturalHeight;
        this.backgroundImage = image;

        this.init();
        this.isRendered = true;
      });
    }
  },
  computed: {
    /**
     * Определеяем смещения канвас относительно страницы
     * @return {{x: *, y: *}}
     */
    computedCanvasOffsetsOnPage() {
      const canvas = this.canvas.getBoundingClientRect();
      return {
        x: parseInt(canvas.left + window.pageXOffset),
        y: parseInt(canvas.top + window.pageYOffset)
      };
    }
  },
  methods: {
    /* Событие нажатия мыши */
    _onMouseDown(e) {
      this.isMouseDown = true;
      this.sourceXMouse = this.getMouseCoord().xMouse;
      this.sourceYMouse = this.getMouseCoord().yMouse;
      this.onMouseDown(e);
    },
    /* Событие движения мыши */
    _onMouseMove(e) {
      this.xMouse = this.getMouseCoord(e).xMouse;
      this.yMouse = this.getMouseCoord(e).yMouse;
      this.deltaXMouse = this.xMouse - this.sourceXMouse;
      this.deltaYMouse = this.yMouse - this.sourceYMouse;
      this.onMouseMove(e);
    },
    /* Событие отжатия мыши */
    _onMouseUp(e) {
      this.isMouseDown = false;
      this.onMouseUp(e);
    },
    /**
     * Событие загрузки изображения по переданному пути
     * @return {Promise<Image>}
     */
    async onloadBackgroundImage() {
      const bgImage = new Image();
      bgImage.src = this.backgroundImagePath;

      return await new Promise((resolve, reject) => {
        bgImage.onload = () => {
          resolve(bgImage);
        };
      });
    },
    /**
     * Определяем координаты мыши относительно канваса
     * @return {{yMouse: *, xMouse: *}}
     */
    getMouseCoord(e = this.mouseEvent) {
      const canvasOffset = this.computedCanvasOffsetsOnPage;

      let xMouse = parseInt(e.pageX ?? e.touches[0].clientX) - canvasOffset.x,
        yMouse = parseInt(e.pageY ?? e.touches[0].clientY) - canvasOffset.y;

      if (xMouse < 0) xMouse = 0;
      if (xMouse > this.canvas.width) xMouse = this.canvas.width;
      if (yMouse < 0) yMouse = 0;
      if (yMouse > this.canvas.height) yMouse = this.canvas.height;

      return { xMouse, yMouse };
    },
    /**
     * Получаем расстояние между двумя точками
     * @param object point1
     * @param object point2
     * @return number
     */
    getDistanceBetweenPoints(point1, point2) {
      return Math.sqrt(Math.pow(point1.x - point2.x, 2) + Math.pow(point1.y - point2.y, 2));
    },
    /**
     * Проверяем принадлежит ли точка полигону и его вершинам
     * @param point
     * @param poly
     * @return {{contain: *, indexAngle: *}}
     */
    isBelongToPoly(point, poly) {
      let i, j, contain = false;

      for (i = 0, j = poly.length - 1; i < poly.length; j = i++) {
        if ((poly[i].y > point.y) !== (poly[j].y > point.y) &&
          (point.x < (poly[j].x - poly[i].x) * (point.y - poly[i].y) / (poly[j].y - poly[i].y) + poly[i].x)) {
          contain = !contain;
        }
      }

      const indexAngle = this.indexSquareAngleOfPoly(point, poly);

      if (indexAngle !== -1) {
        contain = true;
      }

      return { contain, indexAngle };
    },
    /**
     * Определяем индекс вершины полигона, которому принадлежит точка
     * @param point
     * @param poly
     * @param side
     * @returns {*}
     */
    indexSquareAngleOfPoly(point, poly, side = 8) {
      return poly.findIndex(item => (
        item.x - side / 2 <= point.x &&
        item.y - side / 2 <= point.y &&
        item.x + side / 2 >= point.x &&
        item.y + side / 2 >= point.y
      )
      );
    },
    /**
     * Рисуем полигон с вершинами
     * @param object
     * @return void
     */
    drawPoly(object) {
      let { settings, coord } = object;

      this.ctx.beginPath();
      this.ctx.fillStyle = settings.colorBackground;

      coord.forEach((coord, key) => {
        if (key === 0) {
          this.ctx.moveTo(coord.x, coord.y);
        } else {
          this.ctx.lineTo(coord.x, coord.y);
        }
      });

      this.ctx.strokeStyle = settings.colorBorder;
      this.ctx.lineWidth = settings.hasOwnProperty('lineWidth') ? settings.lineWidth : 2;
      this.ctx.stroke();
      this.ctx.closePath();
      this.ctx.fill();

      /* Рисуем вершины полигона */
      if (settings.hasOwnProperty('points')) {
        coord.forEach((coord, key) => {
          if (key) {
            this.drawRect({
              coord,
              settings: settings.points
            });
          }
        });
      }
    },
    /**
     * Рисуем квадрат
     * @param object
     * @return void
     */
    drawRect(object) {
      let { settings, coord } = object;

      this.ctx.fillStyle = settings.colorBackground;
      this.ctx.fillRect(coord.x - settings.width / 2, coord.y - settings.height / 2, settings.width, settings.height);
    },
    /**
     * Рисуем картинку
     * @param image
     * @return void
     */
    drawImage(image) {
      this.ctx.drawImage(image, 0, 0, image.naturalWidth, image.naturalHeight);
    },
    /**
     * Очищаем канвас полностью в зависимости от размеров
     * @return void
     */
    clearCanvas() {
      this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    },
  },
  /**
   * Записываем в состояния canvas и ctx, подписываемся
   * на событие загрузки изображения, если оно есть и
   * запускаем обработчик события при клике по холсту
   */
  mounted() {
    this.canvas = this.$refs.canvas;
    this.ctx = this.canvas.getContext('2d');

    if (this.backgroundImagePath) {
      this.onloadBackgroundImage().then(image => {
        this.canvas.width = image.naturalWidth;
        this.canvas.height = image.naturalHeight;
        this.backgroundImage = image;

        this.init();
        this.isRendered = true;
      });
    } else {
      this.init();
      this.isRendered = true;
    }

    if (!this.disabled) {
      this.canvas.addEventListener('mousedown', (e) => {
        this.mouseEvent = e;
        this._onMouseDown(e);
      });
    }
  }
}

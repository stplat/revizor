export default {
  methods: {
    /* Формат дня недели */
    formatDateHelper(string) {
      const date = new Date(string.replace(/\s/, 'T'));
      const year = date.getFullYear();
      const month = date.getMonth() < 9 ? '0' + Number(date.getMonth() + 1) : Number(date.getDate() + 1);
      const day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();

      return `${year}-${month}-${day}`;
    },

    /* Формат времени */
    formatTimeHelper(string) {
      const date = this.isDateHelper(string) ? string : new Date(string.replace(/\s/, 'T'));
      const hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours();
      const minutes = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
      const seconds = date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds();

      return `${hours}:${minutes}`;
    },

    /* При фокусировки на Input не скролит страницу */
    disableScrollOnFocusIOSHelper(input) {
      input.style.transform = 'translate3d(0, -10000px, 0)';
      setTimeout(() => {
        input.style.transform = 'translate3d(0, 0, 0)';
      }, 1);
    },

    /* Определяем браузер мобильного устройства (IOS) */
    checkIsMobileHelper() {
      return {
        Android: function () {
          return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function () {
          return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function () {
          return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function () {
          return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function () {
          return navigator.userAgent.match(/IEMobile/i);
        }
      };
    },

    /* Анимация сворачивания вверх и вниз для изменения высоты (2 метода, enter, leave)
    * <transition name="slide-down" @enter="animationEnterHelper" @leave="animationLeaveHelper"></transition>
    * */
    animationEnterHelper(element) {
      element.style.height = 'auto';
      const height = getComputedStyle(element).height;
      element.style.height = 0;
      getComputedStyle(element).height;

      requestAnimationFrame(() => {
        element.style.height = height;
      });
    },
    animationLeaveHelper(element) {
      element.style.height = getComputedStyle(element).height;

      requestAnimationFrame(() => {
        element.style.height = 0;
      });
    },
    /* Создаем глубокую копию объекта */
    deepCloneHelper(object) {
      return JSON.parse(JSON.stringify(object));
    },
    /* Меняем положение элементов в массиве */
    arraySwapHelper(array, indexFrom, indexTo) {
      const arr = array.slice();
      arr.splice(Number(indexTo), 0, arr.splice(Number(indexFrom), 1)[0]);
      return arr;
    },
    /* Конвертер HEX в RGB */
    convertHexToRgbHelper(hex, opacity = 100) {
      const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
      return result
        ? { r: parseInt(result[1], 16), g: parseInt(result[2], 16), b: parseInt(result[3], 16), o: opacity / 100 }
        : false;
    },
    /* Конвертер RGBA в HEX */
    convertRgbaToHexHelper(rgb) {
      if (rgb.r > 255 || rgb.g > 255 || rgb.b > 255 || !rgb.r || !rgb.g || !rgb.b) return false;

      function componentToHex(c) {
        const hex = c.toString(16);
        return hex.length === 1 ? '0' + hex : hex;
      }

      return '#' + componentToHex(Number(rgb.r)) + componentToHex(Number(rgb.g)) + componentToHex(Number(rgb.b));
    },
    /* Проверка переменной на строку */
    isStringHelper(val) {
      return (typeof val === "string" || val instanceof String);
    },
    /* Проверка переменной на дату */
    isDateHelper(date) {
      return date && Object.prototype.toString.call(date) === "[object Date]" && !isNaN(date);
    }
  },
  directives: {
    'prevent-number': {
      bind(el, binding) {
        if (binding.value) {
          const inputHandler = function (e) {
            const ch = String.fromCharCode(e.which);
            const re = new RegExp(/[A-Za-zА-Яа-яЁё]/);
            if (ch.match(re)) {
              e.preventDefault();
            }
          };
          el.addEventListener('keypress', inputHandler);
        }
      }
    },
    indeterminate: function (el, binding) {
      el.indeterminate = Boolean(binding.value);
    }
  },
  filters: {}
};

export function serialize(array, name) {
  return array.reduce((item, carry) => {
    return `${String(item)}&${name}[]=${String(carry)}`;
  }, '').replace('&', '');
}

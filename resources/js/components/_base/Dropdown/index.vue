<template>
  <div class="dropdown">
    <template v-if="type === 'search'">
      <div class="dropdown__input">
        <input
          class="input"
          :class="{ 'is-active': shown }"
          :id="id ? id : 'search-dropdown-' + _uid"
          @focus="shown = true"
          @input="$emit('input', $event.target.value, $event)"
        />
      </div>
    </template>
    <template v-else-if="type === 'button'">
      <input
        type="checkbox"
        hidden
        :id="id ? id : 'dropdown-' + _uid"
        v-model="shown"
        :disabled="disabled"
      />
      <label
        class="dropdown__btn"
        :class="computedClassName"
        :for="id ? id : 'dropdown-' + _uid"
        >{{ label }}
      </label>
    </template>
    <div class="dropdown__dropdown" v-if="shown">
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    id: {
      type: String,
      required: false
    },
    type: {
      type: String,
      required: false,
      default: "button",
      validator: value => {
        return ["button", "search"].indexOf(value) !== -1;
      }
    },
    label: {
      type: String,
      required: false
    },
    disabled: {
      type: Boolean,
      required: false
    },
    size: {
      type: String,
      required: false,
      validator: value => {
        return ["slim"].indexOf(value) !== -1;
      }
    },
    hide: {
      type: Boolean,
      required: false
    },
    color: {
      type: String,
      required: false,
      validator: value => {
        return (
          [
            "red",
            "green",
            "yellow",
            "orange",
            "grey",
            "dark-grey",
            "blue"
          ].indexOf(value) !== -1
        );
      }
    }
  },
  data() {
    return {
      shown: false
    };
  },
  methods: {
    /* Прячем подсказку при клике по документу */
    closeOnDocument(e) {
      if (this.$el !== e.target.closest(".dropdown")) {
        this.shown = false;
      }
    }
  },
  computed: {
    computedClassName() {
      let className = "";

      switch (this.size) {
        case "slim":
          className += "dropdown__btn--slim";
      }

      switch (this.color) {
        case "red":
          className += " dropdown__btn--red";
          break;
        case "green":
          className += " dropdown__btn--green";
          break;
        case "yellow":
          className += " dropdown__btn--yellow";
          break;
        case "orange":
          className += " dropdown__btn--orange";
          break;
        case "grey":
          className += " dropdown__btn--grey";
          break;
        case "dark-grey":
          className += " dropdown__btn--dark-grey";
          break;
        case "blue":
          className += " dropdown__btn--blue";
          break;
      }

      return className;
    }
  },
  watch: {
    /* Отслеживаем состояние и навешивание/удаляем обработчик */
    shown(value) {
      if (value && this.disabled !== true) {
        document.addEventListener("click", this.closeOnDocument);
      } else {
        document.removeEventListener("click", this.closeOnDocument);
      }
    },
    hide(value) {
      this.shown = false;
    }
  }
};
</script>

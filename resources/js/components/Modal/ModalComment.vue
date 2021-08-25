<template>
  <div class="card card--small">
    <preloader v-if="isLoading"></preloader>
    <div class="card__header">
      <h3>Комментарии</h3>
    </div>
    <div
      class="card__body"
      :style="{ 'max-height': '400px', 'overflow-y': 'auto' }"
    >
      <div class="comment">
        <div class="comment__input">
          <input-text
            placeholder="Напишите комментарий"
            v-model="data.text"
            @focus="focusComment"
            @blur="blurComment"
            :disabled="disabled"
          ></input-text>
        </div>
        <div class="comment__list">
          <ul class="comment-list">
            <li v-for="(item, key) in computedComments" :key="key">
              <div class="comment-item">
                <div class="comment-item__head">
                  <div class="comment-item__name">{{ item.username }}</div>
                  <div class="comment-item__date">
                    {{ item.date }}
                    {{ item.time }}
                  </div>
                </div>
                <div class="comment-item__text">{{ item.comment }}</div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    disabled: {
      type: Boolean,
      required: false
    }
  },
  data() {
    return {
      data: {
        violation: null,
        text: ""
      },
      isPressed: false,
      isLoading: false
    };
  },
  computed: {
    computedComments() {
      return this.$store.getters["Modal/getComments"].map(item => {
        return Object.assign({}, item, {
          time: this.formatTimeHelper(new Date(item.time + "Z"))
        });
      });
    }
  },
  watch: {
    isPressed(value) {
      if (value) {
        document.addEventListener("keydown", this.pressKeyEnter);
      } else {
        document.removeEventListener("keydown", this.pressKeyEnter);
      }
    }
  },
  methods: {
    focusComment(e) {
      this.isPressed = true;
    },
    blurComment(e) {
      this.isPressed = false;
    },
    pressKeyEnter(e) {
      if (this.isPressed && e.key === "Enter" && this.validate()) {
        this.isPressed = false;
        this.isLoading = true;

        this.$store.dispatch("Modal/storeComment", this.data).then(res => {
          if (res.hasOwnProperty("errors")) {
            this.errors = res.errors;
          } else {
            this.errors = [];
            this.data.text = "";
            this.isPressed = true;
          }

          this.isLoading = false;
        });
      }
    },
    validate() {
      return !!this.data.text;
    }
  },
  mounted() {
    const violation = this.$store.getters["Modal/getViolation"];

    this.data.violation = violation.id;
  }
};
</script>

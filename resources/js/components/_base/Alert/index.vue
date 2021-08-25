<template>
  <transition-group name="alert-list" mode="out-in">
    <div
      class="alert"
      v-for="(message, key) in localMessages"
      :key="_uid + key"
      :class="message.className"
      v-html="message.text"
    ></div>
  </transition-group>
</template>

<script>
export default {
  data() {
    return {
      localTimer: 0,
      localMessages: []
    };
  },
  props: {
    messages: {
      type: Array,
      required: true
      // validator: array => {
      //   return array.every(
      //     item => typeof item === "string" || item instanceof String
      //   );
      // }
    },
    timer: {
      type: Number,
      required: false,
      default: 5000
    }
  },
  watch: {
    messages(value) {
      clearTimeout(this.localTimer);

      this.localMessages = this.mapLocalMessages(value);

      if (this.timer) {
        this.localTimer = setTimeout(
          () => (this.localMessages = []),
          this.timer
        );
      }
    }
  },
  methods: {
    mapLocalMessages(array) {
      return array.map((item, key) => {
        return {
          id: key,
          text: item.hasOwnProperty("text") ? item.text : item,
          className: item.hasOwnProperty("className")
            ? item.className
            : "alert--danger"
        };
      });
    }
  },
  created() {
    this.localMessages = this.mapLocalMessages(this.messages);
  }
};
</script>

<template>
  <view-layout :breadcrumbs="computedBreadcrumbs" :route-name="routeName">
    <template v-slot:title>Проверка распознаваний</template>
    <recognition></recognition>
  </view-layout>
</template>
<script>
import ViewRecognition from "@/stores/modules/ViewRecognition";

export default {
  components: {},
  props: {
    initialData: {
      type: Object,
      required: false
    },
    temp: {},
    routeName: {
      type: String,
      required: true
    }
  },
  data() {
    return {};
  },
  computed: {
    computedBreadcrumbs() {
      return [
        { name: "Главная", slug: "/" },
        { name: "Проверка распознаваний", slug: "" }
      ];
    }
  },
  mounted() {
    document.body.classList.add("is-blocked");

    window.onblur = () => {
      this.$store.dispatch("ViewRecognition/updateCheckingRecognition");
    };

    window.onbeforeunload = () => {
      this.$store.dispatch("ViewRecognition/updateCheckingRecognition");
    };

    document.addEventListener("click", e => {
      if (e.target.nodeName === "A") {
        this.$store
          .dispatch("ViewRecognition/updateCheckingRecognition")
          .then(res => {
            if (e.target.href === this.asset("logout")) {
              axios.post(this.asset("logout")).then(res => {
                window.location.href = this.asset("login");
              });
            } else {
              window.location = e.target.href;
            }
          });
        e.preventDefault();
      }
    });
  },
  created() {
    this.$store.registerModule("ViewRecognition", ViewRecognition);
    // this.$store.commit(
    //   "ViewRecognition/setRecognitionListByCheckBoxes",
    //   this.temp
    // );

    this.$store.commit("ViewRecognition/setUikList", this.initialData.uikList);
    this.$store.commit(
      "ViewRecognition/setRegionList",
      this.initialData.regionList
    );
    this.$store.commit(
      "ViewRecognition/setCountSelectCamera",
      this.initialData.selectCameraCount
    );
    this.$store.commit(
      "ViewRecognition/setCountCheckBoxes",
      this.initialData.checkBoxesCount
    );
  },
  beforeDestroy() {
    this.$store.unregisterModule("ViewRecognition");
  }
};
</script>

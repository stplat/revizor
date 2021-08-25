// import Tip from "@/stores/modules/Tip";

export default {
  created() {
    if (!this.$store.hasModule('Tip')) {
      this.$store.registerModule("Tip", Tip);
    }
  },
  props: {
    title: {
      type: String,
      required: false
    },
  },
}

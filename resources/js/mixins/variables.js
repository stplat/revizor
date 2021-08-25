export default {
  data() {
    return {
      basePath: '',
    }
  },
  mounted() {
    this.basePath = document.querySelector('base').href;
  },
  methods: {
    asset(path) {
      return this.basePath + path;
    }
  }
}

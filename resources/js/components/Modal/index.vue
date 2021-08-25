<template>
  <popup
    @close="clickCloseModal"
    width="1000px"
    :isLoading="isLoading"
    :shown="shown"
  >
    <template v-slot:sign v-if="!isMounting">
      <sign-type :type="computedViolationType"></sign-type>
    </template>
    <template v-slot:header v-if="!isMounting">
      <modal-tip-violation :violation="props.violation"></modal-tip-violation>
    </template>
    <template v-slot:subheader v-if="!isMounting">
      <modal-tip-uik :camera="props.camera"></modal-tip-uik>
    </template>
    <template v-slot:right-side v-if="!isMounting">
      <multiple-select
        :data="computedViolationStatusList"
        :color="computedViolationColor"
        v-model="computedViolationStatus"
        :disabled="blocked"
      ></multiple-select>
    </template>
    <template v-slot:body v-if="!isMounting">
      <alert :messages="messages" v-if="blocked"></alert>
      <div class="card__container">
        <div class="card__col card__col--65">
          <modal-media v-if="computedShowMedia"></modal-media>
          <modal-broadcast
            v-if="computedViolationType === '1'"
            :camera="props.camera"
          ></modal-broadcast>
          <modal-revisor v-if="computedShowRevisor"></modal-revisor>
        </div>
        <div class="card__col card__col--35">
          <modal-claim :disabled="blocked"></modal-claim>
          <modal-comment :disabled="blocked"></modal-comment>
        </div>
      </div>
    </template>
  </popup>
</template>
<script>
import Modal from "@/stores/modules/Modal";

export default {
  components: {
    ModalTipViolation: require("./ModalTipViolation").default,
    ModalTipUik: require("./ModalTipUik").default,
    ModalComment: require("./ModalComment").default,
    ModalClaim: require("./ModalClaim").default,
    ModalMedia: require("./ModalMedia").default,
    ModalBroadcast: require("./ModalBroadcast").default,
    ModalRevisor: require("./ModalRevisor").default
  },
  props: {
    props: {
      type: Object,
      required: true,
      validator: value => {
        return (
          value.hasOwnProperty("check") &&
          value.hasOwnProperty("camera") &&
          value.hasOwnProperty("uik") &&
          value.hasOwnProperty("violation")
        );
      }
    },
    shown: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      data: {
        comment: ""
      },
      isLoading: false,
      isMounting: true,
      errors: [],
      messages: [
        {
          className: "alert--info",
          text: "Данное нарушение блокировано другим пользователем."
        }
      ],
      blocked: true
    };
  },
  mounted() {
    window.onblur = () => {
      if (!this.blocked) {
        const check = this.props.check,
          violation = this.props.violation;

        this.$store.dispatch("Modal/updateViolationBlocked", {
          check,
          violation,
          blocked: 0
        });
      }

      this.$emit("close");
    };
  },
  watch: {
    shown(value) {
      if (value) {
        this.init();
      }
    }
  },
  computed: {
    computedViolationType() {
      return String(this.$store.getters["Modal/getViolation"].type);
    },
    computedViolationStatus: {
      get() {
        return String(this.$store.getters["Modal/getViolation"].status);
      },
      set(status) {
        this.changeViolationStatus(status);
      }
    },
    computedViolationColor() {
      switch (this.computedViolationStatus) {
        case "1":
          return "red";
        case "2":
          return "orange";
        case "3":
          return "yellow";
        case "4":
          return "green";
        case "5":
          return "grey";
        case "6":
          return "dark-grey";
        case "7":
          return "blue";
      }
    },
    computedViolationStatusList() {
      return this.$store.getters["Modal/getViolationStatusList"].map(item => {
        const id = this.computedViolationStatus;
        const value = String(item.id);

        item = {
          value,
          label: item.name,
          name: "status"
        };

        if (id === "1" && !(value === "2" || value === "6")) {
          return Object.assign(item, { disabled: true });
        }

        if (id === "2") {
          return Object.assign(item, { disabled: true });
        }

        if (id === "3" && !(value === "4" || value === "6")) {
          return Object.assign(item, { disabled: true });
        }

        if (id === "4" && !(value === "6")) {
          return Object.assign(item, { disabled: true });
        }

        if (id === "5" && !(value === "6" || value === "7")) {
          return Object.assign(item, { disabled: true });
        }

        if (id === "7") {
          return Object.assign(item, { disabled: true });
        }

        return item;
      });
    },
    computedShowMedia() {
      return (
        this.computedViolationType === "4" || this.computedViolationType === "5"
      );
    },
    computedShowRevisor() {
      return (
        this.computedViolationType === "6" || this.computedViolationType === "7"
      );
    }
  },
  methods: {
    init() {
      this.isMounting = true;
      this.isLoading = true;
      this.$store.dispatch("Modal/show", this.props).then(res => {
        if (res.hasOwnProperty("errors")) {
          this.errors = res.errors;
        } else {
          this.errors = [];
          this.blocked = res.violation.hasOwnProperty("blocked")
            ? res.violation.blocked
            : false;
        }
        this.isMounting = false;
        this.isLoading = false;
      });
    },
    /**
     * Закрываем модалку и отменяем блокировку нарушения
     * @return void
     */
    clickCloseModal() {
      if (!this.blocked) {
        const check = this.props.check,
          violation = this.props.violation;

        this.$store.dispatch("Modal/updateViolationBlocked", {
          check,
          violation,
          blocked: 0
        });
      }

      this.$emit("close");
    },
    changeViolationStatus(status) {
      const violation = this.$store.getters["Modal/getViolation"];
      this.isLoading = true;

      this.$store
        .dispatch("Modal/updateViolation", {
          violation: violation.id,
          violationStatus: status,
          check: this.props.check
        })
        .then(res => {
          if (res.hasOwnProperty("errors")) {
            this.errors = res.errors;
          } else {
            this.errors = [];
          }
          this.isLoading = false;
        });
    }
  },
  created() {
    this.$store.registerModule("Modal", Modal);
  }
};
</script>

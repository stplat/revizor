<template>
  <card size="small">
    <preloader v-if="isLoading"></preloader>
    <alert :messages="errors"></alert>
    <alert :messages="result"></alert>
    <template v-slot:title>Жалоба</template>
    <template v-slot:title-right
      ><tip title="Заголовок подсказки"></tip
    ></template>
    <ul class="list">
      <li class="list__item list__item--key" data-key="Заявитель:">
        <template v-if="computedViolationStatus === 1">
          <input-select
            size="slim"
            :data="computedApplicantList"
            v-model="data.applicant"
            :disabled="disabled"
          ></input-select>
        </template>
        <template v-else>
          {{ computedApplicant }}
        </template>
      </li>
      <li class="list__item list__item--key" data-key="Комиссия:">
        {{ computedCommission }}
      </li>
      <li class="list__item list__item--key" data-key="Голосование:">
        {{ computedVoting }}
      </li>
      <li class="list__item list__item--key" data-key="Постановление:">
        <template v-if="computedViolationStatus === 1">
          <input-select
            size="slim"
            :data="computedDecreeList"
            v-model="data.decree"
            :disabled="disabled"
          ></input-select>
        </template>
        <template v-else>
          {{ computedDecree }}
        </template>
      </li>
      <li
        class="list__item list__item--key list__item--wrap"
        data-key="Содержание:"
      >
        <input-textarea
          v-model="data.content"
          :disabled="computedViolationStatus !== 1 || disabled"
        ></input-textarea>
      </li>
      <li
        class="list__item list__item--key"
        data-key="В работе у пользователя:"
        v-if="computedViolationStatus !== 1"
      >
        {{ computedUsername }}
      </li>
      <li
        class="list__item list__item--key"
        data-key="Сформировать повторно:"
        v-if="computedViolationStatus === 7"
      >
        <input-toggle
          @change="changeReGenerate"
          :disabled="disabled"
        ></input-toggle>
      </li>
      <li class="list__item">
        <template v-if="computedViolationStatus === 1">
          <button
            class="btn btn--primary mr-1"
            @click="clickGenerate"
            :disabled="disabled"
          >
            {{ data.file ? "Далее" : "Сформировать" }}
          </button>
          <button
            class="btn btn--light"
            @click="popup.uploadClaim = true"
            :disabled="disabled"
          >
            Загрузить свою жалобу
          </button>
        </template>
        <template v-if="computedViolationStatus === 3">
          <button
            class="btn btn--primary mr-1"
            @click="popup.uploadResponse = true"
            :disabled="disabled"
          >
            Прикрепить ответ
          </button>
        </template>
        <template
          v-if="computedViolationStatus !== 1 && computedViolationStatus !== 3"
        >
          <button
            class="btn btn--primary mr-1"
            @click="clickDownloadResponse"
            :disabled="disabled"
          >
            Скачать ответ
          </button>
        </template>
        <template v-if="computedViolationStatus !== 1">
          <button
            class="btn btn--light"
            @click="clickDownloadClaim"
            :disabled="disabled"
          >
            Скачать жалобу
          </button>
        </template>
      </li>
    </ul>
    <upload-claim
      v-model="data.file"
      :disabled="disabled"
      :shown="popup.uploadClaim"
      @close="popup.uploadClaim = false"
    ></upload-claim>
    <upload-response
      @result="getResult"
      :disabled="disabled"
      :shown="popup.uploadResponse"
      @close="popup.uploadResponse = false"
    ></upload-response>
  </card>
</template>
<script>
import formValidation from "@/mixins/formValidation";

export default {
  mixins: [formValidation],
  components: {
    UploadClaim: require("./UploadClaim").default,
    UploadResponse: require("./uploadResponse").default
  },
  props: {
    disabled: {
      type: Boolean,
      required: false
    }
  },
  data() {
    return {
      data: {
        check: 1,
        violation: null,
        applicant: "",
        content: "",
        decree: "",
        violationType: "",
        file: ""
      },
      claimLink: "",
      responseLink: "",
      errors: [],
      result: [],
      rules: {
        // applicant: ["required"],
        content: ["required"]
        // decree: ["required"]
      },
      labels: {
        applicant: "Заявитель",
        content: "Содержание",
        decree: "Постановление"
      },
      isLoading: false,
      popup: {
        uploadClaim: false,
        uploadResponse: false
      }
    };
  },
  computed: {
    computedViolationStatus() {
      return this.$store.getters["Modal/getViolation"].status;
    },
    computedApplicantList() {
      return this.$store.getters["Modal/getClaim"].applicantList.map(item => {
        return {
          value: String(item.id),
          name: item.name
        };
      });
    },
    computedCommission() {
      return this.$store.getters["Modal/getClaim"].applicantList.reduce(
        (carry, item) => {
          return String(item.id) === this.data.applicant
            ? item.commission
            : carry;
        },
        "н/д"
      );
    },
    computedVoting() {
      const checkDateEnd = this.$store.getters["Modal/getClaim"].checkEndDate,
        violationDateEnd = this.$store.getters["Modal/getViolation"].date_end;

      return checkDateEnd === violationDateEnd ? "День выборов" : "Досрочное";
    },
    computedDecreeList() {
      return this.$store.getters["Modal/getClaim"].decreeList.map(item => {
        return {
          value: String(item.id),
          name: item.name
        };
      });
    },
    computedDecree() {
      return this.computedDecreeList.reduce((carry, item) => {
        return this.data.decree === item.value ? item.name : carry;
      }, "н/д");
    },
    computedUsername() {
      return this.$store.getters["Modal/getViolation"].username;
    },
    computedClaimLink() {
      return this.$store.getters["Modal/getViolation"].protocol_link;
    },
    computedResponseLink() {
      return this.$store.getters["Modal/getViolation"].response_link;
    },
    computedApplicant() {
      return this.computedApplicantList.reduce((carry, item) => {
        return this.data.applicant === item.value ? item.name : carry;
      }, "н/д");
    }
  },
  methods: {
    clickGenerate() {
      if (this.validate()) {
        this.isLoading = true;
        this.$store.dispatch("Modal/storeClaim", this.data).then(res => {
          if (res.hasOwnProperty("errors")) {
            this.errors = res.errors;
          } else {
            this.errors = [];

            const a = document.createElement("a");
            a.href = res.claimPath;
            a.target = "_blank";
            a.click();
          }

          this.isLoading = false;
        });
      }
    },
    changeReGenerate(e) {
      this.isLoading = true;

      this.$store.dispatch("Modal/restoreClaim", this.data).then(res => {
        if (res.hasOwnProperty("errors")) {
          this.errors = res.errors;
        } else {
          this.errors = [];
        }

        this.isLoading = false;
      });
    },
    clickDownloadClaim() {
      const a = document.createElement("a");
      a.href = this.computedClaimLink;
      a.target = "_blank";
      a.click();
    },
    clickDownloadResponse() {
      const a = document.createElement("a");
      a.href = this.computedResponseLink;
      a.target = "_blank";
      a.click();
    },
    getResult(message) {
      this.userIds = [];
      this.lastId = null;
      this.result = [message];
    }
  },
  mounted() {
    const violation = this.$store.getters["Modal/getViolation"];

    this.data.violation = violation.id;
    this.data.violationType = violation.type;
    this.data.content = violation.text;
    this.data.applicant = String(violation.applicant);
    this.data.decree = String(violation.decree);
  }
};
</script>

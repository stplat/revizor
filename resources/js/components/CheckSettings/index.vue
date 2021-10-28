<template>
  <tip button-type="settings" title="Настройки проверки">
    <div class="tip-container">
      <preloader v-if="isLoading"></preloader>
      <div class="tip-container__half">
        <div class="tip-dropdown__title">Управление проверкой</div>
        <div class="tip-dropdown__content">
          <button
            class="btn btn--warning btn--small"
            @click="changeStatus(false)"
            v-if="computedCheck.active"
          >
            Остановить
          </button>
          <button
            class="btn btn--success btn--small"
            @click="changeStatus(true)"
            v-if="!computedCheck.active"
          >
            Продолжить
          </button>
          <button
            class="btn btn--danger btn--small ml-1"
            @click="modal.remove = true"
          >
            Удалить
          </button>
        </div>
        <div class="tip-dropdown__title">Участки</div>
        <div class="tip-dropdown__text">
          <template v-if="computedCheck.uiks_count">
            Загружено {{ computedCheck.uiks_count }} участка (-ов)
          </template>
          <template v-else>
            Данные не загружены
          </template>
        </div>
        <div class="tip-dropdown__content">
          <div
            class="btn btn--primary btn--small"
            @click="modal.uploadUik = true"
          >
            Загрузить новые
          </div>
        </div>
        <div class="tip-dropdown__title">Проверка работы Ревизора</div>
        <ul class="tip-dropdown__list tip-dropdown__list---light">
          <li data-key="Участки:">
            <div class="tip-dropdown__input">
              <search-checkbox
                :data="computedUiks"
                v-model="data.reviewUiks"
              ></search-checkbox>
            </div>
          </li>
        </ul>
        <button
          class="btn btn--primary btn--small"
          @click="reviewUik"
          :disabled="!data.reviewUiks.length"
        >
          Отправить участки на проверку
        </button>
      </div>
      <div class="tip-container__half">
        <div class="tip-dropdown__title">Официальная явка</div>
        <template v-if="computedCheck.official_votes">
          <div class="tip-dropdown__text">
            Загружено {{ computedCheck.official_votes }} участка (-ов)
          </div>
        </template>
        <template v-else>
          <div class="tip-dropdown__text">Данные не загружены</div>
        </template>
        <div class="tip-dropdown__content">
          <button
            class="btn btn--primary btn--small"
            @click="modal.uploadVote = true"
          >
            Загрузить
          </button>
          <button
            class="btn btn--success btn--small ml-1"
            v-if="computedCheck.official_votes"
            @click="compareTurnout"
          >
            Сравнить явку
          </button>
        </div>
        <div class="tip-dropdown__title">Промежуточная явка</div>
        <template v-if="computedCheck.inter_official_votes">
          <div class="tip-dropdown__text">
            Загружено {{ computedCheck.inter_official_votes }} участка (-ов)
          </div>
        </template>
        <template v-else>
          <div class="tip-dropdown__text">Данные не загружены</div>
        </template>
        <div class="tip-dropdown__content">
          <button
            class="btn btn--primary btn--small"
            @click="modal.uploadIntermediateVote = true"
          >
            Загрузить
          </button>
          <button
            class="btn btn--success btn--small ml-1"
            v-if="computedCheck.inter_official_votes"
            @click="compareIntermediateTurnout"
          >
            Сравнить явку
          </button>
        </div>
        <div class="tip-dropdown__title">Ручной подсчет явки</div>
        <ul class="tip-dropdown__list tip-dropdown__list---light">
          <li data-key="Участки:">
            <div class="tip-dropdown__input">
              <search-checkbox
                :data="computedUiks"
                v-model="data.countingUiks"
              ></search-checkbox>
            </div>
          </li>
        </ul>
        <button
          class="btn btn--primary btn--small"
          @click="countingUik"
          :disabled="!data.countingUiks.length"
        >
          Отправить участки на подсчёт
        </button>
      </div>
    </div>
    <template v-slot:outer>
      <check-remove
        :shown="modal.remove"
        @close="modal.remove = false"
      ></check-remove>
      <uik-upload
        :shown="modal.uploadUik"
        @close="modal.uploadUik = false"
      ></uik-upload>
      <official-vote-upload
        :shown="modal.uploadVote"
        @close="modal.uploadVote = false"
      ></official-vote-upload>
      <intermediate-vote-upload
        :shown="modal.uploadIntermediateVote"
        @close="modal.uploadIntermediateVote = false"
      ></intermediate-vote-upload>
    </template>
  </tip>
</template>

<script>
export default {
  components: {
    CheckRemove: require("./CheckRemove"),
    UikUpload: require("./UikUpload"),
    OfficialVoteUpload: require("./OfficialVoteUpload"),
    IntermediateVoteUpload: require("./IntermediateVoteUpload")
  },
  props: {},
  data() {
    return {
      data: {
        id: null,
        cam: "",
        reviewUiks: [],
        countingUiks: []
      },
      modal: {
        remove: false,
        uploadUik: false,
        uploadVote: false,
        uploadIntermediateVote: false
      },
      isLoading: false
    };
  },
  computed: {
    computedCheck() {
      return this.$store.getters["ViewCheck/getCheck"];
    },
    computedUiks() {
      return this.$store.getters["ViewCheck/getUikList"];
    }
  },
  methods: {
    /* Изменить статус проверки */
    changeStatus(status) {
      const id = this.computedCheck.check_id;

      this.isLoading = true;
      this.$store
        .dispatch("ViewCheck/editCheck", { id, active: status })
        .then(res => {
          if (!res.hasOwnProperty("errors")) {
          }

          this.isLoading = false;
        });
    },
    /* Отправить участки на проверку */
    reviewUik() {
      const id = this.computedCheck.check_id;

      this.isLoading = true;
      this.$store
        .dispatch("ViewCheck/reviewUik", {
          check_id: id,
          uiks: this.data.reviewUiks
        })
        .then(res => {
          if (!res.hasOwnProperty("errors")) {
          }

          this.isLoading = false;
        });
    },
    /* Сравнить явку ревизора с официальной */
    compareTurnout() {
      const id = this.computedCheck.check_id;

      this.isLoading = true;
      this.$store
        .dispatch("ViewCheck/compareTurnout", { check_id: id })
        .then(res => {
          if (!res.hasOwnProperty("errors")) {
          }

          this.isLoading = false;
        });
    },
    /* Сравнить промежуточную явку ревизора с официальной */
    compareIntermediateTurnout() {
      const id = this.computedCheck.check_id;

      this.isLoading = true;
      this.$store
        .dispatch("ViewCheck/compareIntermediateTurnout", { check_id: id })
        .then(res => {
          if (!res.hasOwnProperty("errors")) {
          }

          this.isLoading = false;
        });
    },
    /* Отправить участки на подсчет явки */
    countingUik() {
      const id = this.computedCheck.check_id;

      this.isLoading = true;
      this.$store
        .dispatch("ViewCheck/countingUik", {
          check_id: id,
          uiks: this.data.countingUiks
        })
        .then(res => {
          if (!res.hasOwnProperty("errors")) {
          }

          this.isLoading = false;
        });
    }
  },
  created() {}
};
</script>

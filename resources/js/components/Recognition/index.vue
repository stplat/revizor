<template>
  <div class="layout__container">
    <alert :messages="messages"></alert>
    <card>
      <preloader v-if="computedIsLoading"></preloader>
      <template v-slot:title>Критерии выгрузки</template>
      <div class="card__container">
        <div class="card__col">
          <div class="card__title">
            Параметры выгрузки распознаваний
            <tip title="Подсказка" direction="left"></tip>
          </div>
          <ul class="list">
            <li class="list__item list__item--key" data-key="Вид:">
              <m-radio-dropdown
                :data="computedTypeList"
                v-model="data.type"
              ></m-radio-dropdown>
            </li>
            <li class="list__item list__item--key" data-key="Выгружать за раз:">
              <m-select
                :data="computedCountList"
                v-model="data.limit"
              ></m-select>
            </li>
          </ul>
        </div>
        <div class="card__col">
          <div class="card__title">
            Выбор участков
            <tip title="Подсказка" direction="right"></tip>
          </div>
          <ul class="list">
            <li class="list__item list__item--key" data-key="Участок:">
              <m-checkbox-dropdown
                type="xls"
                :data="computedUikList"
                v-model="data.uiks"
              ></m-checkbox-dropdown>
            </li>
            <li class="list__item list__item--key" data-key="Регион:">
              <m-checkbox-dropdown
                type="xls"
                :data="computedRegionList"
                v-model="data.regions"
              ></m-checkbox-dropdown>
            </li>
          </ul>
        </div>
      </div>
      <button
        class="btn btn--primary"
        :disabled="computedDisabledBtn"
        @click="clickUploadOrder"
      >
        Выгрузить
      </button>
    </card>
    <order-select-cam
      v-if="orderType === '1'"
      :params="data"
      @messages="
        messages = [
          {
            className: 'alert--success',
            text: 'Выгруженные участки успешно проверены!'
          }
        ]
      "
    ></order-select-cam>
    <order-check-boxes
      v-if="orderType === '2'"
      :params="data"
      @messages="
        messages = [
          {
            className: 'alert--success',
            text: 'Выгруженные участки успешно проверены!'
          }
        ]
      "
    ></order-check-boxes>
  </div>
</template>
<script>
export default {
  components: {
    OrderSelectCam: require("./RecognitionOrderSelectCam/index.vue").default,
    OrderCheckBoxes: require("./RecognitionOrderCheckBoxes/index.vue").default
  },
  data() {
    return {
      data: {
        type: "",
        limit: "5",
        uiks: [],
        regions: []
      },
      messages: [],
      orderType: "",
      isLoading: false
    };
  },
  methods: {
    /**
     * Загружаем отчет по выбранным параметрам
     * @return void
     */
    clickUploadOrder() {
      this.$store.commit("ViewRecognition/setIsLoading", true);
      this.orderType = "";

      this.$store
        .dispatch("ViewRecognition/showReport", this.data)
        .then(res => {
          if (res.hasOwnProperty("errors")) {
            this.messages = res.errors;
          } else {
            this.orderType = this.data.type;
            this.messages = res.length
              ? []
              : ["По запросу с указанными параметрами ничего не найдено!"];
          }

          this.$store.commit("ViewRecognition/setIsLoading", false);
        });
    }
  },
  computed: {
    /**
     * Общий прелоадер для всех карточек у отчетов
     * @return boolean
     */
    computedIsLoading() {
      return this.$store.getters["ViewRecognition/getIsLoading"];
    },
    /* Активность кнопки выгрузки отчета */
    computedDisabledBtn() {
      let { type, limit, uiks, regions } = this.data;
      return !type || !limit || !uiks.length || !regions.length;
    },
    /* Список типов проверок */
    computedTypeList() {
      const countSelectCamera = this.$store.getters[
        "ViewRecognition/getCountSelectCamera"
      ];

      const countCheckBoxes = this.$store.getters[
        "ViewRecognition/getCountCheckBoxes"
      ];

      return [
        {
          id: 1,
          value: "1",
          label: "Выбор камеры",
          name: "type",
          tag: String(countSelectCamera),
          disabled: !countSelectCamera
        },
        {
          id: 2,
          value: "2",
          label: "Проверка ящиков",
          name: "type",
          tag: String(countCheckBoxes),
          disabled: !countCheckBoxes
        }
        // {
        //   id: 3,
        //   value: "3",
        //   label: "Проверка работы ревизора",
        //   name: "type",
        //   tag: "?"
        // }
      ];
    },
    /* Список количества выгружаемых данных */
    computedCountList() {
      return [
        { id: 1, value: "5", label: "5" },
        { id: 2, value: "10", label: "10" },
        { id: 3, value: "20", label: "20" },
        { id: 4, value: "30", label: "30" }
      ];
    },
    /* Список участков */
    computedUikList() {
      const uikList = this.$store.getters["ViewRecognition/getUikList"];
      this.data.uiks = uikList.map(item => String(item.id));

      return uikList.map(item =>
        Object.assign(item, { value: String(item.id), label: item.name })
      );
    },
    /* Список участков */
    computedRegionList() {
      const regionList = this.$store.getters["ViewRecognition/getRegionList"];
      this.data.regions = regionList.map(item => String(item.id));

      return regionList.map(item =>
        Object.assign(item, { value: String(item.id), label: item.name })
      );
    }
  }
};
</script>

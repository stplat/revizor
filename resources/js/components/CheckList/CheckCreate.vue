<template>
  <popup @close="$emit('close')" :isLoading="isLoading" :shown="shown">
    <template v-slot:header>Создание новой проверки</template>
    <template v-slot:body>
      <alert :messages="errors"></alert>
      <v-form-new :data="computedForm" v-model="data"></v-form-new>
    </template>
    <template v-slot:footer>
      <button class="btn btn--primary ml-auto" @click="create">Добавить</button>
    </template>
  </popup>
</template>

<script>
import formValidation from "@/mixins/formValidation";

export default {
  mixins: [formValidation],
  data() {
    return {
      data: {
        check_type: "",
        name: "",
        date: {
          from: "",
          to: ""
        },
        auth_needed: false,
        auth_type: "",
        auth_link: [],
        auth_login: "",
        auth_password: "",
        csv: "",
        decree: ""
      },
      rules: {
        check_type: ["required"],
        name: ["required"],
        date: ["required"],
        auth_link: ["required"],
        csv: ["required"]
      },
      labels: {
        check_type: "Тип проверки",
        name: "Название",
        date: "Дата голосования",
        auth_link: "Ссылка(и) на каталоги видео",
        csv: "Загрузка участков"
      },
      isLoading: false,
      shown: false
    };
  },
  mounted() {
    setTimeout(() => (this.shown = true));
  },
  methods: {
    create() {
      if (this.validate()) {
        this.isLoading = true;
        const date_start = this.data.date.from,
          date_end = this.data.date.to;

        const data = Object.assign({}, this.data, {
          date_start,
          date_end,
          auth_needed: this.data.auth_needed ? true : ""
        });

        this.$store.dispatch("ViewCheckList/createCheck", data).then(res => {
          if (res.hasOwnProperty("errors")) {
            this.errors = res.errors;
          } else {
            this.$emit("result", {
              text: `Проверка ${this.data.name} успешно создана!`,
              className: "alert--success"
            });
            this.$emit("close");
          }
          this.isLoading = false;
        });
      }
    }
  },
  computed: {
    computedForm() {
      const types = [
        { value: "3", label: "Онлайн" },
        { value: "2", label: "Оффлайн" }
      ];

      const authTypes = this.$store.getters[
        "ViewCheckList/getAuthTypeList"
      ].map(item => ({
        value: String(item.id),
        label: item.name
      }));

      return [
        {
          type: "select",
          name: "check_type",
          label: "Вид проверки:",
          data: types
        },
        { type: "text", name: "name", label: "Название:" },
        { type: "date-period", name: "date", label: "Дата голосования:" },
        {
          type: "multi-textbox",
          name: "auth_link",
          label: "Ссылка(и) на каталоги видео:"
        },
        {
          type: "toggle",
          name: "auth_needed",
          label: "Необходима авторизация:"
        },
        {
          type: "select",
          name: "auth_type",
          label: "Вид авторизации:",
          data: authTypes
        },
        { type: "text", name: "auth_login", label: "Логин:" },
        { type: "password", name: "auth_password", label: "Пароль:" },
        {
          type: "file",
          name: "csv",
          label: "Загрузка участков:",
          linkToExample: this.asset(
            "storage/upload_layouts/stations_example.csv"
          )
        },
        {
          type: "text",
          name: "decree",
          label: "Постановление:",
          desc:
            "Введите название постановления, на которое ссылаться при формировании протоколов нарушений"
        }
      ].filter(
        form =>
          !(
            ["auth_type", "auth_login", "auth_password"].find(
              item => item === form.name
            ) && !this.data.auth_needed
          )
      );
    }
  }
};
</script>

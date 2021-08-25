<template>
  <div class="layout__container">
    <alert :messages="result" />
    <card>
      <template v-slot:title>Список проверок</template>
      <v-client-table
        :data="table.data"
        :columns="table.columns"
        :options="table.options"
      >
        <template v-slot:afterLimit>
          <button class="btn btn--success" @click="modal.create = true">
            Создать проверку
          </button>
        </template>
        <template v-slot:actions="props">
          <div class="vuetable__icons">
            <a :href="/checks/ + props.row.id" title="Открыть проверку">
              <unicon name="presentation-check" fill="royalblue" />
            </a>
            <a
              href=""
              @click.prevent="modalRemoveCheck(props.row)"
              title="Удалить проверку"
            >
              <unicon name="trash-alt" fill="royalblue" />
            </a>
          </div>
        </template>
      </v-client-table>
    </card>
    <check-create
      v-if="modal.create"
      @close="modal.create = false"
      @result="getResult"
    ></check-create>
    <check-delete
      :id="modal.delete.id"
      v-if="modal.delete.show"
      @close="modal.delete.show = false"
      @result="getResult"
    ></check-delete>
  </div>
</template>
<script>
import CheckCreate from "./CheckCreate";
import CheckDelete from "./ChecksDelete";

export default {
  components: {
    CheckCreate,
    CheckDelete
  },
  data() {
    return {
      modal: {
        create: false,
        delete: {
          id: null,
          name: "",
          show: false
        }
      },
      result: []
    };
  },
  methods: {
    getResult(message) {
      this.result = [message];
    },
    modalRemoveCheck(obj) {
      this.modal.delete = Object.assign(
        {},
        { id: obj.id, name: obj.name, show: true }
      );
    }
  },
  computed: {
    table() {
      const headings = {
        key: "№",
        name: "Название",
        uiks: "Участки",
        cameras: "Камеры",
        status: "Статус",
        type: "Вид проверки",
        actions: "Действия"
      };

      const data = this.$store.getters["ViewCheckList/getCheckList"].map(
        (item, i) => ({
          key: i + 1,
          id: item.check_id,
          name: item.check_name,
          uiks: item.uiks_count,
          cameras: item.cameras_count,
          status: item.data_type_name,
          type: item.type_name,
          actions: "Действия"
        })
      );

      return {
        data,
        options: { headings, _data: data },
        columns: Object.keys(headings)
      };
    }
  }
};
</script>

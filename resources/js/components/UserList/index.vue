<template>
  <div class="layout__container">
    <alert :messages="result"></alert>
    <card>
      <template v-slot:title>Список пользователей</template>
      <v-client-table
        :data="table.data"
        :columns="table.columns"
        :options="table.options"
        ref="table"
      >
        <template v-slot:afterLimit>
          <button class="btn btn--primary mr-1" @click="openModalCreate">
            Добавить пользователей
          </button>
          <button
            class="btn btn--danger"
            @click="openModalDelete()"
            :disabled="!userIds.length"
          >
            Удалить
          </button>
        </template>
        <template v-slot:checkbox="props">
          <input-checkbox
            type="blue"
            :value="String(props.row.id)"
            v-model="userIds"
            @mousedown="selectRow"
          ></input-checkbox>
        </template>
        <template v-slot:permissions="props">
          <toggle-list :data="props.row.permissions">Ограничен </toggle-list>
        </template>
        <template v-slot:allowedChecks="props">
          <template v-if="props.row.allowedChecks === 'all'">
            Полный доступ
          </template>
          <template v-else-if="!props.row.allowedChecks.length">
            Нет доступных проверок
          </template>
          <toggle-checkbox-list :data="props.row.allowedChecks" v-else
            >Ограничен
          </toggle-checkbox-list>
        </template>
        <template v-slot:actions="props">
          <a
            href=""
            class="fa fa-edit"
            @click.prevent="openModalEdit(props.row.id)"
            title="Редактировать"
          >
            <unicon name="pen" fill="royalblue" />
          </a>
          <a
            href=""
            class="fa fa-edit"
            @click.prevent="openModalDelete(props.row.id)"
            title="Удалить"
          >
            <unicon name="trash-alt" fill="royalblue" />
          </a>
        </template>
      </v-client-table>
    </card>
    <user-create
      v-if="modal.create"
      @close="modal.create = false"
      @result="getResult"
    ></user-create>
    <user-edit
      v-if="modal.edit.show"
      @close="modal.edit.show = false"
      @result="getResult"
      :id="modal.edit.id"
    ></user-edit>
    <user-delete
      v-if="modal.delete.show"
      @close="modal.delete.show = false"
      @result="getResult"
      :ids="modal.delete.ids"
    ></user-delete>
  </div>
</template>

<script>
import UserCreate from "./UserCreate";
import UserEdit from "./UserEdit";
import UserDelete from "./UserDelete";

export default {
  components: {
    UserCreate,
    UserEdit,
    UserDelete
  },
  data() {
    return {
      userIds: [],
      lastId: null,
      modal: {
        create: false,
        edit: {
          show: false,
          id: null
        },
        delete: {
          show: false,
          ids: []
        }
      },
      result: []
    };
  },
  mounted() {
    document.addEventListener("keydown", e => {
      if (e.key === "Shift") {
        document.querySelector("table").style.userSelect = "none";
      }
    });

    document.addEventListener("keyup", e => {
      if (e.key === "Shift") {
        document.querySelector("table").style.userSelect = "auto";
      }
    });
  },
  methods: {
    openModalCreate() {
      this.modal.create = true;
    },
    openModalEdit(id) {
      this.modal.edit.id = id;
      this.modal.edit.show = true;
    },
    openModalDelete(id = null) {
      this.modal.delete.ids = id ? [id] : this.userIds;
      this.modal.delete.show = true;
    },
    getResult(message) {
      this.userIds = [];
      this.lastId = null;
      this.result = [message];
    },
    selectRow(e) {
      const value = Number(e.target.value);

      if (this.userIds.length && this.lastId && e.shiftKey) {
        this.userIds = this.$refs.table.filteredData
          .map(item => item.id)
          .filter(item => {
            item = Number(item);

            if (value > this.lastId) {
              return item >= this.lastId && item < value;
            } else {
              return item <= this.lastId && item > value;
            }
          });
      }

      if (!e.shiftKey) {
        this.lastId = !e.target.checked ? value : this.lastId;
      }
    }
  },
  computed: {
    table() {
      const headings = {
        checkbox: "",
        key: "№",
        id: "ID",
        name: "Юзернейм",
        login: "Логин",
        role: "Группа",
        permissions: "Доступ",
        allowedChecks: "Проверки",
        recognitions: "Проверено распознаваний",
        violations: "Подано жалоб",
        createdBy: "Создан",
        actions: "Действия"
      };

      const data = this.$store.getters["ViewUser/getUserList"].map(
        (item, i) => ({
          checkbox: "",
          key: i + 1,
          id: item.id,
          name: item.name,
          login: item.login,
          role: item.role,
          permissions: item.permissions,
          allowedChecks: item.allowed_checks,
          recognitions: item.box_recognitions_count,
          violations: item.violation_status_changes_count,
          createdBy: item.created_by,
          actions: ""
        })
      );

      return {
        data,
        options: {
          headings,
          _data: data,
          sortByColumn: true,
          sortable: ["key", "date"]
        },
        columns: Object.keys(headings)
      };
    }
  }
};
</script>

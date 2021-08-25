<template>
  <div :class="`VueTables VueTables--${props.source}`" slot-scope="props">
    <div :class="props.theme.row">
      <div :class="props.theme.column">
        <div class="table__top">
          <vnodes :vnodes="props.slots.beforeLimit" />
          <template v-if="props.opts._data.length > 10">
            <div class="VueTables__limit table__limit">
              <vt-per-page-selector />
              <!--              <button class="btn btn-outline-primary" @click="download" v-if="props.opts._data && props.opts.isNeedDownloadButton === undefined">Экспорт</button>-->
            </div>
          </template>
          <vnodes :vnodes="props.slots.afterLimit"></vnodes>

          <div
            v-if="!props.opts.filterByColumn && props.opts.filterable"
            class="VueTables__search table__search"
          >
            <vnodes :vnodes="props.slots.beforeFilter" />
            <vt-generic-filter />
            <vnodes :vnodes="props.slots.afterFilter" />
          </div>
          <vnodes :vnodes="props.slots.afterFilterWrapper" />

          <div
            class="VueTables__pagination-wrapper"
            v-if="props.opts.pagination.dropdown && props.totalPages > 1"
          >
            <div
              :class="
                `${props.theme.field} ${props.theme.inline} ${props.theme.right} VueTables__dropdown-pagination`
              "
            >
              <vt-dropdown-pagination />
            </div>
          </div>

          <div
            v-if="props.opts.columnsDropdown"
            :class="
              `VueTables__columns-dropdown-wrapper ${props.theme.right} ${props.theme.dropdown.container}`
            "
          >
            <vt-columns-dropdown />
          </div>
        </div>
      </div>
    </div>

    <vnodes :vnodes="props.slots.beforeTable" />
    <div class="table__responsive">
      <vt-table ref="vt_table" />
    </div>
    <vnodes :vnodes="props.slots.afterTable" />

    <vt-pagination />
  </div>
</template>

<script>
import VtColumnsDropdown from "vue-tables-2/compiled/components/VtColumnsDropdown";
import VtDropdownPagination from "vue-tables-2/compiled/components/VtDropdownPagination";
import VtGenericFilter from "vue-tables-2/compiled/components/VtGenericFilter";
import VtPerPageSelector from "vue-tables-2/compiled/components/VtPerPageSelector";
import VtPagination from "vue-tables-2/compiled/components/VtPagination";
import VtTable from "vue-tables-2/compiled/components/VtTable";

export default {
  name: "MyDataTable",
  props: {
    props: Object,
    data: Object,
    columns: Object
  },
  methods: {
    /*
     * Выгрузка таблицы в xlsx
     *
     * */
    async download() {
      const data = {
        data: this.props.opts._data,
        headings: this.props.opts.headings
      };
      const res = await axios
        .post("/table/export", data)
        .catch(error => console.log(error));

      const a = document.createElement("a");
      a.href = await res.data;
      a.click();
    }
  },
  components: {
    VtGenericFilter,
    VtPerPageSelector,
    VtColumnsDropdown,
    VtDropdownPagination,
    VtTable,
    VtPagination,
    vnodes: {
      functional: true,
      render: (h, ctx) => ctx.props.vnodes
    }
  },
  mounted() {}
};
</script>
<style lang="scss">
.VueTables__search-field input {
  display: block;
  width: 100%;
  height: 38px;
  padding: 5px 10px;
  font-size: 14px;
  font-weight: 400;
  line-height: 1;
  color: #3c4b64;
  background-color: #ffffff;
  border: 1px solid #c5c9cd;
  border-radius: 4px;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;

  &:focus,
  &.is-active {
    color: #495057;
    background-color: #ffffff;
    border-color: #a1cbef;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25);
  }

  &.is-invalid {
    border-color: #dc3545;
    padding-right: 45px;
    position: relative;
    background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545'%3E%3Ccircle cx='6' cy='6' r='4.5'/%3E%3Cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3E%3Ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px;

    &:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
  }
}
</style>

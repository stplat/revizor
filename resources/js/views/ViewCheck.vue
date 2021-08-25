<template>
  <view-layout :breadcrumbs="computedBreadcrumbs" :route-name="routeName">
    <template v-slot:title>{{ initialData.check.check_name }}</template>
    <template v-slot:tip>
      <check-info></check-info>
      <check-settings class="ml-2"></check-settings>
    </template>
    <div class="layout__container">
      <card>
        <template v-slot:title>Прогресс работы Ревизора</template>
        <check-schema></check-schema>
      </card>
      <card>
        <template v-slot:title>Участковые избирательные комиссии</template>
        <check-uik></check-uik>
      </card>
      <card>
        <template v-slot:title>События</template>
        <check-events></check-events>
      </card>
    </div>
  </view-layout>
</template>

<script>
import ViewCheck from "@/stores/modules/ViewCheck";

export default {
  props: {
    initialData: {
      type: Object,
      required: true
    },
    routeName: {
      type: String,
      required: true
    }
  },
  data() {
    return {};
  },
  computed: {
    computedBreadcrumbs() {
      return [
        { name: "Главная", slug: "/" },
        { name: "Список проверок", slug: "checks" },
        { name: this.initialData.check.check_name, slug: "" }
      ];
    }
  },
  created() {
    this.$store.registerModule("ViewCheck", ViewCheck);
    this.$store.commit("ViewCheck/setCheck", this.initialData.check);
    this.$store.commit("ViewCheck/setUiks", this.initialData.uiks);
    this.$store.commit(
      "ViewCheck/setViolationsForEventTable",
      this.initialData.violationsForEventTable
    );

    this.$store.commit(
      "ViewCheck/setUikList",
      this.initialData.filterList.uikList
    );
    this.$store.commit(
      "ViewCheck/setRegionList",
      this.initialData.filterList.regionList
    );
    this.$store.commit(
      "ViewCheck/setBoxTypeList",
      this.initialData.filterList.boxTypeList
    );
    this.$store.commit(
      "ViewCheck/setViolationTypeList",
      this.initialData.filterList.violationTypeList
    );
    this.$store.commit(
      "ViewCheck/setViolationStatusList",
      this.initialData.filterList.violationStatusList
    );
    this.$store.commit("ViewCheck/setViolations", this.initialData.violations);
    this.$store.commit(
      "ViewCheck/setViolationsGroupByName",
      this.initialData.violationsGroupByName
    );
  },
  beforeDestroy() {
    this.$store.unregisterModule("ViewCheck");
    this.$store.unregisterModule("Tip");
  }
};
</script>

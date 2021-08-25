<template>
  <tip button-type="info" title="Информация о проверке">
    <div class="tip-dropdown__title">Основное</div>
    <ul class="tip-dropdown__list">
      <li data-key="Вид проверки:">{{ computedCheck.check_type }}</li>
      <li data-key="Период:">{{ computedCheck.period }}</li>
      <li data-key="Создана:">{{ computedCheck.start_datetime }}</li>
      <li data-key="Завершена:">{{ computedCheck.end_datetime }}</li>
    </ul>
  </tip>
</template>
<script>

  export default {
    computed: {
      computedCheck() {
        return this.$store.getters['ViewCheck/getCheck'];
      },
    }
  }
</script>

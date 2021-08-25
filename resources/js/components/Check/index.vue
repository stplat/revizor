<template>
  <div class="layout__container">
    <card>
      <template v-slot:title>Прогресс работы Ревизора</template>
      <schema></schema>
    </card>
    <card>
      <template v-slot:title>Участковые избирательные комиссии</template>
      <uik></uik>
    </card>
    <card>
      <template v-slot:title>События</template>
      <event></event>
    </card>
  </div>
</template>
<script>
  import Event from './CheckEvent';
  import Schema from './CheckSchema';
  import Uik from './CheckUik';

  export default {
    components: {
      Event, Schema, Uik
    },
  }

</script>

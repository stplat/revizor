<template>
  <div class="input-number">
    <div class="input-number__input">
      <input
        ref="number"
        type="number"
        class="form-control"
        :min="min"
        :max="max"
        :id="id"
        :value="value"
        :name="name"
        v-prevent-number="true"
        @input="$emit('input', Number($event.target.value), $event)"
      >
      <div class="input-number__btn">
        <button class="input-number__spin"
                @click="stepUp($event)"
                :name="name"
        ></button>
        <button class="input-number__spin input-number__spin--bottom"
                @click="stepDown($event)"
                :name="name"
        ></button>
      </div>
    </div>

  </div>
</template>
<script>
  export default {
    model: {
      prop: 'value',
      event: 'input'
    },
    props: {
      id: {
        type: String,
        required: false
      },
      name: {
        type: String,
        required: false
      },
      min: {
        type: Number,
        required: false,
        default: 1
      },
      max: {
        type: Number,
        required: false,
        default: 999999999
      },
      value: {
        type: Number,
        required: false
      }
    },
    methods: {
      stepUp(e) {
        const number = this.$refs.number.value;

        if (Number(number) + 1 < this.max) {
          this.$emit('input', Number(number) + 1, e)
        }
      },
      stepDown(e) {
        const number = this.$refs.number.value;

        if (Number(number) - 1 >= this.min) {
          this.$emit('input', Number(number) - 1, e)
        }
      }
    }
  }
</script>

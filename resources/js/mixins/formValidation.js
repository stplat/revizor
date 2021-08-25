export default {
  data() {
    return {
      errorMessages: {
        required: 'Поле <strong></strong> обязательно для заполнения'
      },
      errors: [],
      rules: [],
      labels: []
    }
  },
  methods: {
    generateMessage(rule, field) {
      if (this.errorMessages.hasOwnProperty(rule)) {
        const label = this.labels.hasOwnProperty(field) ? this.labels[field] : field;
        return this.errorMessages[rule].replace('<strong></strong>', `<strong>${label}</strong>`);
      }
    },
    sendMessage(rule, field) {
      const message = this.generateMessage(rule, field);
      const errorIndex = this.errors.findIndex(item => item === message);

      this.$set(this.errors, errorIndex !== -1 ? errorIndex : this.errors.length, message);
    },
    deleteMessage(rule, field) {
      const message = this.generateMessage(rule, field);
      const errorIndex = this.errors.findIndex(item => item === message);

      if (errorIndex !== -1) {
        this.errors.splice(errorIndex, 1);
      }
    },
    validate() {
      let validator = true;
      for (let prop in this.rules) {
        if (this.rules.hasOwnProperty(prop) && this.data.hasOwnProperty(prop)) {
          const value = this.data[prop];

          this.rules[prop].forEach(rule => {
            if (typeof rule === 'function') {
              if (rule(value, this.data, this, prop) === false) validator = false;
            }

            if (this.ruleHandler.hasOwnProperty(rule)) {
              if (this.ruleHandler[rule](value, this.data, this, prop) === false) validator = false;
            }
          });
        }
      }

      return validator;
    }
  },
  computed: {
    ruleHandler() {
      return {
        required: function (value, data, self, propName = '') {
          if ((Array.isArray(value) && !value.length) || !value) {
            self.sendMessage('required', propName);
            return false;
          } else if (value === Object(value)) {

          } else {
            self.deleteMessage('required', propName);
          }
        },
        callback: function (value, data, self, propName = '') {
          console.log(value, data, self, propName);
        }
      };
    }
  }
}

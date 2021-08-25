<template>
  <div class="user">
    <div class="user__btn">
      <input type="checkbox" id="user" hidden v-model="dropped" />
      <label for="user">
        <img :src="asset('images/admin_avatar.png')" alt="" />
      </label>
    </div>
    <transition name="slide-fade" mode="in-out">
      <div class="user__dropdown" v-if="dropped">
        <div class="user-dropdown">
          <div class="user-dropdown__title">Настройки</div>
          <ul class="user-dropdown__list">
            <li>
              <a
                :href="asset('settings')"
                class="user-dropdown__link user-dropdown__link--setting"
                >Настройки</a
              >
            </li>
          </ul>
          <hr />
          <ul class="user-dropdown__list">
            <li>
              <a
                :href="asset('logout')"
                class="user-dropdown__link user-dropdown__link--logout"
                @click.prevent="logout"
                >Выход</a
              >
            </li>
          </ul>
        </div>
      </div>
    </transition>
  </div>
</template>
<script>
export default {
  data() {
    return {
      dropped: false
    };
  },
  watch: {
    /* Отслеживаем состояние и навешивание/удаляем обработчик */
    dropped(value) {
      if (value) {
        document.addEventListener("click", this.closeOnDocument);
      } else {
        document.removeEventListener("click", this.closeOnDocument);
      }
    }
  },
  methods: {
    logout() {
      if (!document.body.classList.contains("is-blocked")) {
        axios.post(this.asset("logout")).then(res => {
          window.location.href = this.asset("login");
        });
      }
    },
    /* Прячем подсказку при клике по документу */
    closeOnDocument(e) {
      if (this.$el !== e.target.closest(".user")) {
        this.dropped = false;
      }
    }
  }
};
</script>

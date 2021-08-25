import axios from 'axios';

export default {
  namespaced: true,
  state: {
    token: {},
    tokens: [],
  },
  actions: {
    /* Получение пользователя */
    async updateToken({ commit }, payload) {
      let { id } = payload;
      const res = await axios.get(this.state.requestPath + '/tokens/' + id).catch(err => console.log('In token/updateToken - ' + err));

      if (!res.data.errors) {
        commit('setToken', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Получение списка токенов */
    async updateTokens({ commit }) {
      const res = await axios.get(this.state.requestPath + '/tokens').catch(err => console.log('In token/updateTokens - ' + err));
      commit('setTokens', res.data);
    },
    /* Создание пользователя */
    async createToken({ commit }, payload) {
      const res = await axios.post(this.state.requestPath + '/tokens', payload)
        .catch(err => console.log('In token/createToken - ' + err));

      if (!res.data.errors) {
        commit('setTokens', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Редактирование пользователя */
    async editToken({ commit }, payload) {
      let { id, token_id, server_id, token } = payload;
      const res = await axios.put(this.state.requestPath + '/tokens/' + id, { id, token_id, server_id, token_api: token })
        .catch(err => console.log('In token/editToken - ' + err));

      if (!res.data.errors) {
        commit('setTokens', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Удаление пользователя */
    async deleteToken({ commit }, payload) {
      let { id } = payload;
      const res = await axios.delete(this.state.requestPath + '/tokens/' + id)
        .catch(err => console.log('In token/deleteUser - ' + err));

      if (!res.data.errors) {
        commit('setTokens', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    }
  },
  mutations: {
    setToken: (state, obj) => state.token = obj,
    setTokens: (state, array) => state.tokens = array,
    setRoles: (state, array) => state.roles = array
  },
  getters: {
    getToken: (state) => state.token,
    getTokens: (state) => state.tokens,
    getRoles: (state) => state.roles
  }
};

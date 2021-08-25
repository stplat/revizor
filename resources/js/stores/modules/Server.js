import axios from 'axios';

export default {
  namespaced: true,
  state: {
    server: {},
    servers: [],
    serverTypes: []
  },
  actions: {
    /* Получение сервера */
    async updateServer({ commit }, payload) {
      let { id } = payload;
      const res = await axios.get(this.state.requestPath + '/servers/' + id).catch(err => console.log('In server/updateServer - ' + err));

      if (!res.data.errors) {
        commit('setServer', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Получение списка серверов */
    async updateServers({ commit }) {
      const res = await axios.get(this.state.requestPath + '/servers').catch(err => console.log('In server/updateServers - ' + err));
      commit('setServers', res.data);
    },
    /* Создание сервера */
    async createServer({ commit }, payload) {
      const res = await axios.post(this.state.requestPath + '/servers', payload)
        .catch(err => console.log('In server/createServer - ' + err));

      if (!res.data.errors) {
        commit('setServers', res.data.servers);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Редактирование сервера */
    async editServer({ commit }, payload) {
      let { id, server_id, password } = payload;
      const res = await axios.put(this.state.requestPath + '/servers/' + id, { id, server_id, password })
        .catch(err => console.log('In server/editServer - ' + err));

      if (!res.data.errors) {
        commit('setServers', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Удаление сервера */
    async deleteServer({ commit }, payload) {
      let { id } = payload;
      const res = await axios.delete(this.state.requestPath + '/servers/' + id)
        .catch(err => console.log('In server/deleteServer - ' + err));

      if (!res.data.errors) {
        commit('setServers', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Получение списка типов серверов */
    async updateServerTypes({ commit }) {
      const res = await axios.get(this.state.requestPath + '/servers/index-server-types').catch(err => console.log('In server/indexServerTypes - ' + err));
      commit('setServerTypes', res.data);
    },
  },
  mutations: {
    setServer: (state, obj) => state.server = obj,
    setServers: (state, array) => state.servers = array,
    setServerTypes: (state, array) => state.serverTypes = array,
    setRoles: (state, array) => state.roles = array
  },
  getters: {
    getServer: (state) => state.server,
    getServers: (state) => state.servers,
    getServerTypes: (state) => state.serverTypes,
    getRoles: (state) => state.roles
  }
};

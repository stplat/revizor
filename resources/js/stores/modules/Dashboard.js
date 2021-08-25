import axios from 'axios';

export default {
  namespaced: true,
  state: {
    dashboard: {},
  },
  actions: {
    /* Получение заявителей */
    async updateDashboard({ commit }, payload) {
      const res = await axios.post(this.state.requestPath + '/charts').catch(err => console.log('In dashboard/updateDashboard - ' + err));

      if (!res.data.errors) {
        commit('setDashboard', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
  },
  mutations: {
    setDashboard: (state, obj) => state.dashboard = obj,
  },
  getters: {
    getDashboard: (state) => state.dashboard,
  }
};

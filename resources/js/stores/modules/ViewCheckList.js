import axios from 'axios';

export default {
  namespaced: true,
  state: {
    checkList: [],
    authTypeList: [],
  },
  actions: {
    /* Создание проверки */
    async createCheck({ commit }, payload) {
      const formData = new FormData();
      for (let param in payload) {
        if (payload.hasOwnProperty(param)) {
          const field = Array.isArray(payload[param]) ? JSON.stringify(payload[param]) : payload[param];
          formData.append(param, field);
        }
      }

      const res = await axios.post('/checks', formData)
        .catch(err => console.log('In ViewCheckList/createCheck - ' + err));

      if (!res.data.errors) {
        commit('setCheckList', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Удаление проверки */
    async deleteCheck({ commit }, payload) {
      let { id } = payload;
      const res = await axios.delete(this.state.requestPath + '/checks/' + id)
        .catch(err => console.log('In ViewCheckList/deleteCheck - ' + err));

      if (!res.data.errors) {
        commit('setCheckList', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    }
  },
  mutations: {
    setCheckList: (state, array) => state.checkList = array,
    setAuthTypeList: (state, array) => state.authTypeList = array,
  },
  getters: {
    getCheckList: (state) => state.checkList,
    getAuthTypeList: (state) => state.authTypeList,
  }
};

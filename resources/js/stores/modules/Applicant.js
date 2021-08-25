import axios from 'axios';

export default {
  namespaced: true,
  state: {
    applicant: {},
    applicants: [],
  },
  actions: {
    /* Получение заявителей */
    async updateApplicant({ commit }, payload) {
      let { id } = payload;
      const res = await axios.get(this.state.requestPath + '/applicants/' + id).catch(err => console.log('In applicant/updateApplicant - ' + err));

      if (!res.data.errors) {
        commit('setApplicant', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Создание заявителя */
    async createApplicant({ commit }, payload) {
      let { name, organization } = payload;
      const res = await axios.post(this.state.requestPath + '/applicants', { name, organization })
        .catch(err => console.log('In applicant/createApplicant - ' + err));

      if (!res.data.errors) {
        commit('setApplicants', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Редактирование пользователя */
    async editApplicant({ commit }, payload) {
      let { id, name, organization } = payload;
      const res = await axios.put(this.state.requestPath + '/applicants/' + id, { name, organization })
        .catch(err => console.log('In applicant/editApplicant - ' + err));

      if (!res.data.errors) {
        commit('setApplicants', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Удаление пользователя */
    async deleteApplicant({ commit }, payload) {
      let { id } = payload;
      const res = await axios.delete(this.state.requestPath + '/applicants/' + id)
        .catch(err => console.log('In applicant/deleteApplicant - ' + err));

      if (!res.data.errors) {
        commit('setApplicants', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    }
  },
  mutations: {
    setApplicant: (state, obj) => state.applicant = obj,
    setApplicants: (state, array) => state.applicants = array,
    setRoles: (state, array) => state.roles = array,
  },
  getters: {
    getApplicant: (state) => state.applicant,
    getApplicants: (state) => state.applicants,
    getRoles: (state) => state.roles,
  }
};

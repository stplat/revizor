export default {
  namespaced: true,
  state: {
    check: {},
    uiks: [],
    uikList: [],
    regionList: [],
    boxTypeList: [],
    violationTypeList: [],
    violationStatusList: [],
    violations: [],
    violationsForEventTable: [],
    violationsGroupByName: [],
    uikMap: {}
  },
  actions: {
    /* Получение Событий (нарушений) */
    async showViolations({ commit }, payload) {
      let { check, statuses } = payload;

      const res = await axios.get(this.state.requestPath + '/checks/violations', { params: payload })
        .catch(err => console.log('In ViewCheck/showViolations - ' + err));

      if (!res.data.errors) {
        commit('setViolationsForEventTable', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Изменени проверки */
    async editCheck({ commit }, payload) {
      let { id, active } = payload;

      const res = await axios.put(this.state.requestPath + `/checks/${id}`, { active })
        .catch(err => console.log('In ViewCheck/editCheck - ' + err));

      if (!res.data.errors) {
        commit('setCheck', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Обновление (получение) состояния УИКов */
    async updateUiks({ commit }, payload) {
      let { } = payload;

      const res = await axios.post(this.state.requestPath + '/checks/filter-uiks', payload)
        .catch(err => console.log('In ViewCheck/updateUiks - ' + err));

      if (!res.data.errors) {
        commit('setUiks', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    // /* Обновление (получение) состояния Событий (violations) */
    // async updateViolations({ commit }, payload) {
    //   let { check_id, regions, votes, officialVotes, percentVotes, boxTypes, uiks } = payload;

    //   const res = await axios.post(this.state.requestPath + '/checks/filter-violations', payload)
    //     .catch(err => console.log('In ViewCheck/updateViolations - ' + err));

    //   if (!res.data.errors) {
    //     commit('setViolations', res.data.violations);
    //     return res.data;
    //   } else {
    //     return { errors: Object.values(res.data.errors).map(item => item[0]) };
    //   }
    // },
    /* Удаление проверки */
    async deleteCheck({ commit }, payload) {
      let { id } = payload;

      const res = await axios.delete(this.state.requestPath + '/checks/' + id)
        .catch(err => console.log('In ViewCheck/updateViolations - ' + err));

      if (!res.data.errors) {
        // commit('setChecks', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Загрузка участков */
    async uploadUik({ commit }, payload) {
      const formData = new FormData();
      for (let param in payload) {
        if (payload.hasOwnProperty(param)) {
          formData.append(param, payload[param]);
        }
      }

      const res = await axios.post('/checks/upload-uik', formData)
        .catch(err => console.log('In ViewCheck/uploadUik - ' + err));

      if (!res.data.errors) {
        // commit('setChecks', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Отправка участков на проверку */
    async reviewUik({ commit }, payload) {
      let { check_id, uiks } = payload;

      const res = await axios.post(this.state.requestPath + '/checks/review-uik', { check_id, uiks })
        .catch(err => console.log('In ViewCheck/reviewUik - ' + err));

      if (!res.data.errors) {
        // commit('setChecks', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Загрузка официальной явки */
    async uploadOfficialVote({ commit }, payload) {
      const formData = new FormData();
      for (let param in payload) {
        if (payload.hasOwnProperty(param)) {
          formData.append(param, payload[param]);
        }
      }

      const res = await axios.post('/checks/upload-official-vote', formData)
        .catch(err => console.log('In ViewCheck/uploadUik - ' + err));

      if (!res.data.errors) {
        // commit('setChecks', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Сравнение явки Ревизора с официальной */
    async compareTurnout({ commit }, payload) {
      let { check_id } = payload;

      const res = await axios.post(this.state.requestPath + '/checks/compare-turnout', { check_id })
        .catch(err => console.log('In ViewCheck/reviewUik - ' + err));

      if (!res.data.errors) {
        // commit('setChecks', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Загрузка промежуточной официальной явки */
    async uploadIntermediateVote({ commit }, payload) {
      const formData = new FormData();
      for (let param in payload) {
        if (payload.hasOwnProperty(param)) {
          formData.append(param, payload[param]);
        }
      }

      const res = await axios.post('/checks/upload-intermediate-vote', formData)
        .catch(err => console.log('In ViewCheck/uploadIntermediateVote - ' + err));

      if (!res.data.errors) {
        // commit('setChecks', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Сравнение промежуточной явки Ревизора с официальной */
    async compareIntermediateTurnout({ commit }, payload) {
      let { check_id } = payload;

      const res = await axios.post(this.state.requestPath + '/checks/compare-intermediate-turnout', { check_id })
        .catch(err => console.log('In ViewCheck/reviewUik - ' + err));

      if (!res.data.errors) {
        // commit('setChecks', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Отправка участков на проверку */
    async countingUik({ commit }, payload) {
      let { check_id, uiks } = payload;

      const res = await axios.post(this.state.requestPath + '/checks/counting-uik', { check_id, uiks })
        .catch(err => console.log('In ViewCheck/countingUik - ' + err));

      if (!res.data.errors) {
        // commit('setChecks', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },

  },
  mutations: {
    setCheck: (state, obj) => state.check = obj,
    setUiks: (state, array) => state.uiks = array,
    setUikList: (state, array) => state.uikList = array,
    setRegionList: (state, array) => state.regionList = array,
    setBoxTypeList: (state, array) => state.boxTypeList = array,
    setViolationTypeList: (state, array) => state.violationTypeList = array,
    setViolationsForEventTable: (state, array) => state.violationsForEventTable = array,
    setViolationStatusList: (state, array) => state.violationStatusList = array,
    setViolations: (state, array) => state.violations = array,
    setViolationsGroupByName: (state, array) => state.violationsGroupByName = array,
    setUikMap: (state, obj) => state.uikMap = obj,
  },
  getters: {
    getCheck: (state) => state.check,
    getUiks: (state) => state.uiks,
    getUikList: (state) => state.uikList,
    getRegionList: (state) => state.regionList,
    getBoxTypeList: (state) => state.boxTypeList,
    getViolationTypeList: (state) => state.violationTypeList,
    getViolationsForEventTable: (state) => state.violationsForEventTable,
    getViolationStatusList: (state) => state.violationStatusList,
    getViolations: (state) => state.violations,
    getViolationsGroupByName: (state) => state.violationsGroupByName,
    getUikMap: (state) => state.uikMap,
  }
};

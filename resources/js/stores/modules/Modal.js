export default {
  namespaced: true,
  state: {
    violation: {},
    constant: {},
    officialVotes: {},
    intermediateOfficialVotes: {},
    uik: {},
    violationStatusList: [],
    claim: {},
    comments: [],
    images: [],
    cameraList: [],
    revisorVotes: [],
    realVotes: [],
    violationList: [],
    videoTimes: [],
  },
  actions: {
    /* Получаем все необходимые параметры для модалки */
    async show({ commit }, payload) {
      let { violation } = payload;

      const res = await axios.get(this.state.requestPath + `/modals/show/`, { params: payload })
        .catch(err => console.log('In Modal/show - ' + err));

      if (!res.data.errors) {
        commit('setViolation', res.data.violation);
        commit('setConstant', res.data.constant);
        commit('setOfficialVotes', res.data.officialVotes);
        commit('setUik', res.data.uik);
        commit('setViolationStatusList', res.data.violationStatusList);
        commit('setClaim', res.data.claim);
        commit('setComments', res.data.comments);
        commit('setImages', res.data.images);
        commit('setCameraList', res.data.cameraList);
        commit('setRevisorVotes', res.data.revisorVotes);
        commit('setRealVotes', res.data.realVotes);
        commit('setRealVotes', res.data.realVotes);
        commit('setIntermediateOfficialVotes', res.data.intermediateOfficialVotes);
        commit('setVideoTimes', res.data.videoTimes);
        commit('setViolationList', res.data.violationListWithNotVideo);

        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Меняем статус события */
    async updateViolation({ commit }, payload) {
      let { check, violation, violatioStatus } = payload;

      const res = await axios.put(this.state.requestPath + `/modals/violations`, payload)
        .catch(err => console.log('In Modal/updateViolation - ' + err));

      if (!res.data.errors) {
        commit('setViolation', res.data.violationForModal);
        commit('ViewCheck/setViolationsForEventTable', res.data.violationForEventTable, { root: true });
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Генерируем жалобу */
    async storeClaim({ commit }, payload) {
      let { applicant, content, decree, violation } = payload;
      const formData = new FormData();
      for (let param in payload) {
        if (payload.hasOwnProperty(param)) {
          formData.append(param, payload[param]);
        }
      }

      const res = await axios.post(this.state.requestPath + `/modals/claim`, formData)
        .catch(err => console.log('In Modal/storeClaim - ' + err));

      if (!res.data.errors) {
        commit('setViolation', res.data.violation);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Загружаем ответ на жалобу */
    async uploadClaimResponse({ commit }, payload) {
      let { file } = payload;
      const formData = new FormData();
      for (let param in payload) {
        if (payload.hasOwnProperty(param)) {
          formData.append(param, payload[param]);
        }
      }

      const res = await axios.post(this.state.requestPath + `/modals/claim/upload-response`, formData)
        .catch(err => console.log('In Modal/uploadClaimResponse - ' + err));

      if (!res.data.errors) {
        commit('setViolation', res.data.violation);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Генерируем жалобу повторно */
    async restoreClaim({ commit }, payload) {
      let { applicant, content, decree, violation } = payload;

      const res = await axios.delete(this.state.requestPath + `/modals/claim`, { data: { violation } })
        .catch(err => console.log('In Modal/restoreClaim - ' + err));

      if (!res.data.errors) {
        commit('setViolation', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },

    /* Создаем новый комментарий комментарии */
    async storeComment({ commit }, payload) {
      let { violation, text } = payload;

      const res = await axios.post(this.state.requestPath + `/modals/comments`, payload)
        .catch(err => console.log('In Modal/storeComment - ' + err));

      if (!res.data.errors) {
        commit('setComments', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Получаем видео */
    async showVideo({ commit }, payload) {
      let { camera, dateFrom, dateTo } = payload;

      const res = await axios.get(this.state.requestPath + `/modals/video`, { params: payload })
        .catch(err => console.log('In Modal/showMedia - ' + err));

      if (!res.data.errors) {
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Получаем список нарушений для модалки "Не хватает видео" */
    async showViolationList({ commit }, payload) {
      let { duration, camera } = payload;

      const res = await axios.get(this.state.requestPath + `/modals/violations/list`, { params: payload })
        .catch(err => console.log('In Modal/showViolationList - ' + err));

      if (!res.data.errors) {
        commit('setViolationList', res.data)
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Обновляем статус блокировки */
    async updateViolationBlocked({ commit }, payload) {
      let { check, violation, blocked } = payload;

      const res = await axios.put(this.state.requestPath + `modals/violations`, payload)
        .catch(err => console.log('In ViewRecognition/updateViolationBlocked - ' + err));

      if (!res.data.errors) {
        commit('ViewCheck/setViolationsForEventTable', res.data, { root: true });
        return res;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
  },
  mutations: {
    setViolation: (state, object) => state.violation = object,
    setConstant: (state, object) => state.constant = object,
    setOfficialVotes: (state, object) => state.officialVotes = object,
    setUik: (state, object) => state.uik = object,
    setViolationStatusList: (state, array) => state.violationStatusList = array,
    setClaim: (state, object) => state.claim = object,
    setComments: (state, array) => state.comments = array,
    setImages: (state, array) => state.images = array,
    setCameraList: (state, array) => state.cameraList = array,
    setRevisorVotes: (state, array) => state.revisorVotes = array,
    setRealVotes: (state, array) => state.realVotes = array,
    setIntermediateOfficialVotes: (state, object) => state.intermediateOfficialVotes = object,
    setViolationList: (state, array) => state.violationList = array,
    setVideoTimes: (state, array) => state.videoTimes = array,
  },
  getters: {
    getViolation: (state) => state.violation,
    getConstant: (state) => state.constant,
    getOfficialVotes: (state) => state.officialVotes,
    getUik: (state) => state.uik,
    getViolationStatusList: (state) => state.violationStatusList,
    getClaim: (state) => state.claim,
    getComments: (state) => state.comments,
    getImages: (state) => state.images,
    getCameraList: (state) => state.cameraList,
    getRevisorVotes: (state) => state.revisorVotes,
    getRealVotes: (state) => state.realVotes,
    getIntermediateOfficialVotes: (state) => state.intermediateOfficialVotes,
    getViolationList: (state) => state.violationList,
    getVideoTimes: (state) => state.videoTimes,
  }
}

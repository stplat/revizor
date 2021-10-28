import axios from 'axios';

export default {
  namespaced: true,
  state: {
    uikList: [],
    regionList: [],
    countSelectCamera: null,
    countCheckBoxes: null,
    uikListBySelectCamera: [],
    recognitionListByCheckBoxes: [],
    isLoading: false,
    recognitionIds: []
  },
  actions: {
    /* Выгружаем отчет по распознаваниями */
    async showReport({ commit, getters }, payload) {
      let { } = payload;
      payload.recognitionIds = getters['getRecognitionIds'];

      const res = await axios.get(this.state.requestPath + `recognitions/report`, { params: payload })
        .catch(err => console.log('In ViewRecognition/showReport - ' + err));

      if (!res.data.errors) {
        switch (payload.type) {
          case '1':
            commit('setUikListBySelectCamera', res.data.report);
            commit('setCountSelectCamera', res.data.count);
            break;
          case '2':
            commit('setRecognitionListByCheckBoxes', res.data.report);
            commit('setCountCheckBoxes', res.data.count);
            break;
        }

        commit('setRecognitionIds', res.data.recognitionIds);
        return res.data.report;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Обновляем данные по ящикам */
    async updateBox({ commit, getters }, payload) {
      let { current, params } = payload;
      payload.recognitionIds = getters['getRecognitionIds'];
      console.log(payload)
      const res = await axios.put(this.state.requestPath + `recognitions/box`, payload)
        .catch(err => console.log('In ViewRecognition/updateBox - ' + err));
      console.log(res)
      if (!res.data.errors) {
        switch (params.type) {
          case '1':
            commit('setUikListBySelectCamera', res.data.report);
            commit('setCountSelectCamera', res.data.count);
            break;
          case '2':
            commit('setRecognitionListByCheckBoxes', res.data.report);
            commit('setCountCheckBoxes', res.data.count);
            break;
        }

        commit('setRecognitionIds', res.data.recognitionIds);
        return res.data.report;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Показывает предыдущее распознавание */
    async showPrevRecognition({ commit }, payload) {
      let { } = payload;

      const res = await axios.get(this.state.requestPath + `recognitions/recognition/prev`, { params: payload })
        .catch(err => console.log('In ViewRecognition/showPrevRecognition - ' + err));

      if (!res.data.errors) {
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Обновляем статус блокировки */
    async updateCheckingRecognition({ commit, getters }) {
      const payload = {};

      payload.recognitions = getters['getRecognitionIds'];

      if (getters['getRecognitionIds'] && getters['getRecognitionIds'].length) {
        const res = await axios.put(this.state.requestPath + `recognitions/checking`, payload)
          .catch(err => console.log('In ViewRecognition/updateCheckingRecognition - ' + err));

        if (!res.data.errors) {
          commit('setUikListBySelectCamera', []);
          commit('setRecognitionListByCheckBoxes', []);
          return res;
        } else {
          return { errors: Object.values(res.data.errors).map(item => item[0]) };
        }
      }
    },
  },
  mutations: {
    setUikList: (state, array) => state.uikList = array,
    setRegionList: (state, array) => state.regionList = array,
    setCountSelectCamera: (state, number) => state.countSelectCamera = number,
    setCountCheckBoxes: (state, number) => state.countCheckBoxes = number,
    setUikListBySelectCamera: (state, array) => state.uikListBySelectCamera = array,
    setRecognitionListByCheckBoxes: (state, array) => state.recognitionListByCheckBoxes = array,
    setIsLoading: (state, bool) => state.isLoading = bool,
    setRecognitionIds: (state, array) => state.recognitionIds = array,
  },
  getters: {
    getUikList: (state) => state.uikList,
    getRegionList: (state) => state.regionList,
    getCountSelectCamera: (state) => state.countSelectCamera,
    getCountCheckBoxes: (state) => state.countCheckBoxes,
    getUikListBySelectCamera: (state) => state.uikListBySelectCamera,
    getRecognitionListByCheckBoxes: (state) => state.recognitionListByCheckBoxes,
    getIsLoading: (state) => state.isLoading,
    getRecognitionIds: (state) => state.recognitionIds,
  }
};

import axios from 'axios';

export default {
  namespaced: true,
  state: {
    check: null,
    event: {},
    events: [],
    eventsByType: [],
    eventsByCheck: [],
    checkedEventsByRole: [],
    violations: [],
    violationsByRegion: [],
    violationsByStatus: [],
    violationsByType: [],
    violation: {},
  },
  actions: {
    /* Получение событий */
    async updateEvents({ commit, state }, payload) {
      const res = await axios.get(this.state.requestPath + '/events?check_id=' + state.check)
        .catch(err => console.log('In event/updateEvents - ' + err));

      if (!res.data.errors) {
        commit('setEvents', res.data.events);
        commit('setEventsByType', res.data.eventsByType);
        commit('setEventsByCheck', res.data.eventsByCheck);
        commit('setCheckedEventsByRole', res.data.checkedEventsByRole);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Получение события */
    async updateEvent({ commit, state }, payload) {
      let { id } = payload;
      const res = await axios.get(this.state.requestPath + '/events/' + id)
        .catch(err => console.log('In event/updateEvents - ' + err));

      if (!res.data.errors) {
        commit('setEvent', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Получение нарушений */
    async updateViolations({ commit, state }, payload) {
      const res = await axios.get(this.state.requestPath + '/events/violations?check_id=' + state.check)
        .catch(err => console.log('In event/updateViolations - ' + err));

      if (!res.data.errors) {
        commit('setViolations', res.data.violations);
        commit('setViolationsByRegion', res.data.violationsByRegion);
        commit('setViolationsByStatus', res.data.violationsByStatus);
        commit('setViolationsByType', res.data.violationsByType);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Получение нарушения */
    async updateViolation({ commit, state }, payload) {
      let { id } = payload;
      const res = await axios.get(this.state.requestPath + '/events/violation/' + id)
        .catch(err => console.log('In event/updateViolation - ' + err));

      if (!res.data.errors) {
        commit('setViolation', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
  },
  mutations: {
    setEvent: (state, obj) => state.event = obj,
    setEvents: (state, array) => state.events = array,
    setEventsByType: (state, array) => state.eventsByType = array,
    setEventsByCheck: (state, array) => state.eventsByCheck = array,
    setCheckedEventsByRole: (state, array) => state.checkedEventsByRole = array,
    setCheck: (state, number) => state.check = number,
    setViolations: (state, array) => state.violations = array,
    setViolationsByRegion: (state, array) => state.violationsByRegion = array,
    setViolationsByStatus: (state, array) => state.violationsByStatus = array,
    setViolationsByType: (state, array) => state.violationsByType = array,
    setViolation: (state, obj) => state.violation = obj,
  },
  getters: {
    getEvent: (state) => state.event,
    getEvents: (state) => state.events,
    getEventsByType: (state) => state.eventsByType,
    getEventsByCheck: (state) => state.eventsByCheck,
    getCheckedEventsByRole: (state) => state.checkedEventsByRole,
    getCheck: (state) => state.check,
    getViolations: (state) => state.violations,
    getViolationsByRegion: (state) => state.violationsByRegion,
    getViolationsByStatus: (state) => state.violationsByStatus,
    getViolationsByType: (state) => state.violationsByType,
    getViolation: (state) => state.violation,
  }
};

import axios from 'axios';

export default {
  namespaced: true,
  state: {
    user: {},
    userList: [],
    roleList: [],
    permissionList: [],
    checkList: []
  },
  actions: {
    /* Создание пользователя */
    async createUser({ commit }, payload) {
      let {} = payload;
      const res = await axios.post(this.state.requestPath + '/users', payload)
        .catch(err => console.log('In ViewUser/createUser - ' + err));

      if (!res.data.errors) {
        commit('setUserList', res.data.userList);
        return res.data.path;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Получения пользователя по id */
    async updateUser({ commit }, payload) {
      let { id } = payload;
      const res = await axios.get(this.state.requestPath + '/users/' + id)
        .catch(err => console.log('In ViewUser/updateUser - ' + err));

      if (!res.data.errors) {
        commit('setUser', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Редактирование пользователя */
    async editUser({ commit }, payload) {
      let { id } = payload;
      const res = await axios.put(this.state.requestPath + '/users/' + id, payload)
        .catch(err => console.log('In ViewUser/editUser - ' + err));

      if (!res.data.errors) {
        commit('setUserList', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    },
    /* Удаление пользователя */
    async deleteUser({ commit }, payload) {
      let { id } = payload;
      const res = await axios.delete(this.state.requestPath + '/users', { data: { user: id } })
        .catch(err => console.log('In ViewUser/deleteUser - ' + err));

      if (!res.data.errors) {
        commit('setUserList', res.data);
        return res.data;
      } else {
        return { errors: Object.values(res.data.errors).map(item => item[0]) };
      }
    }
  },
  mutations: {
    setUserList: (state, array) => state.userList = array,
    setRoleList: (state, array) => state.roleList = array,
    setPermissionList: (state, array) => state.permissionList = array,
    setCheckList: (state, obj) => state.checkList = obj,
    setUser: (state, obj) => state.user = obj,
  },
  getters: {
    getUserList: (state) => state.userList,
    getRoleList: (state) => state.roleList,
    getPermissionList: (state) => state.permissionList,
    getCheckList: (state) => state.checkList,
    getUser: (state) => state.user,
  }
};

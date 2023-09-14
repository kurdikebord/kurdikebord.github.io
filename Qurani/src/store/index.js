import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    logged: false,
    user: {},
  },
  getters: {
  },
  mutations: {
    updateUserData(state, data) {
      state.user = data;
    },
    updateUserStatus(state, status) {
      state.logged = status;
    },
  },
  actions: {
    triggerThemeData() {
      const body = document.querySelector('body');
      const storedTheme = window.localStorage.getItem('theme_for_qurani');

      if(storedTheme) {
        if(storedTheme === 'dark') body.classList.add('dark');
        else body.classList.remove('dark');

        return;
      }

      window.localStorage.setItem('theme_for_qurani', 'light');
    },
  },
  modules: {
  }
})

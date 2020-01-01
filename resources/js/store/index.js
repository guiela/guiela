import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersistence from 'vuex-persist'

import file from './modules/file.module'

Vue.use(Vuex)

const vuexLocal = new VuexPersistence({
  storage: window.localStorage,
  options: {
    key: 'guiela'
  }
})

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    file
  },
  plugins: [vuexLocal.plugin]
})
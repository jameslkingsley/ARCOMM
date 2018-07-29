import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import auth from './auth'

const debug = process.env.NODE_ENV !== 'production'

const store = new Vuex.Store({
    strict: debug,

    modules: {
        auth,
    }
})

export default store

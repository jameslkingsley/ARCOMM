import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import auth from './auth'
import mission from './mission'

const debug = process.env.NODE_ENV !== 'production'

const store = new Vuex.Store({
    strict: debug,

    modules: {
        auth,
        mission,
    }
})

export default store

import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import auth from './auth'
import mission from './mission'
import comment from './comment'

const debug = process.env.NODE_ENV !== 'production'

const store = new Vuex.Store({
    strict: debug,

    modules: {
        auth,
        mission,
        comment,
    }
})

export default store

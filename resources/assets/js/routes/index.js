import VueRouter from 'vue-router'
import { sync } from 'vuex-router-sync'
import store from '../store'

import Missions from '../pages/mission/Library.vue'

import mission from './mission'

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/hub', component: Missions, meta: { auth: true } },
        ...mission,
    ]
})

sync(store, router)

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.auth) && !store.state.auth.me) {
        return
    }

    next()
})

export default router

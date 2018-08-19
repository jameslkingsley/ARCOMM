require('./bootstrap')
require('./filters')
require('./ui')

Vue.component('grid', require('./components/Grid.vue'))
Vue.component('grid-child', require('./components/GridChild.vue'))
Vue.component('modal', require('./components/helpers/Modal.vue'))

import App from './pages/App.vue'
import Welcome from './pages/Welcome.vue'

import { mapState } from 'vuex'
import store from './store'
import router from './routes'

Vue.router = router

Vue.use({
    install(Vue, options) {
        Vue.prototype.$auth = function (field = null) {
            if (!window.app.$store) return undefined

            const user = window.app.$store.state.auth.me

            if (field && user) {
                return user[field] || undefined
            }

            return user || undefined
        }
    }
})

window.app = new Vue({
    store,
    router,

    el: '#app',

    data: {
        loading: true,
        progress: null,
        csrfToken: window.App.csrfToken,
    },

    computed: {
        ...mapState({
            route: state => state.route,
            me: state => state.auth.me || optional(),
            auth: state => state.auth.me || optional(),
        })
    },

    created() {
        if (window.App.access_token) {
            localStorage.setItem('access_token', window.App.access_token)
            delete window.App.access_token
        }

        this.$router.beforeEach((to, from, next) => {
            if (to.path.split('/').length <= 3) {
                Events.fire('banner', null)
            }

            return next()
        })

        this.$store.dispatch('auth/checkLogin').then(() => {
            this.loading = false
            this.$router.replace(window.location.pathname)
        }).catch(() => {
            this.loading = false
        })
    }
})

window._ = require('lodash')

window.ajax = require('axios')
window.ajax.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
    window.ajax.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

window.ajax.interceptors.request.use(config => {
    config['headers'] = {
        Accept: 'application/json',
        Authorization: 'Bearer ' + localStorage.getItem('access_token'),
    }

    return config
}, error => Promise.reject(error))

window.moment = require('moment')

window.Vue = require('vue')
Vue.config.productionTip = false

import VueRouter from 'vue-router'
window.VueRouter = VueRouter
Vue.use(VueRouter)

import SvgIcon from 'vue-svgicon'
Vue.use(SvgIcon, { tagName: 'svgicon' })
import './icons'

require('./event')

window.random = a => {
    return a[Math.floor((Math.random() * a.length))]
}

window.opt = window.optional = object => {
    object = object || {}
    return new Proxy(object || {}, {
        get(target, name) {
            return name in target ? target[name] : null
        }
    })
}

if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual'
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

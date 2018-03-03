require('./bootstrap');

import App from './components/App.vue';

import Missions from './components/mission/Library.vue';
import Mission from './components/mission/Index.vue';

Vue.component('grid', require('./components/Grid.vue'));
Vue.component('grid-child', require('./components/GridChild.vue'));

const app = new Vue({
    el: '#app',
    render: h => h(App),
    router: new VueRouter({
        mode: 'history',
        routes: [
            { name: 'Hub', path: '/hub', component: Missions },
            { name: 'Library', path: '/hub/missions', component: Missions },
            { name: 'Mission', path: '/hub/missions/example', component: Mission },
        ]
    }),

    created() {
        this.$router.beforeEach((to, from, next) => {
            if (from.name === 'Mission' && to.component !== 'Mission') {
                Events.fire('banner', null);
                return next();
            }

            next();
        });
    }
});

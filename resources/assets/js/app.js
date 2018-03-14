require('./bootstrap');
require('./filters');

import App from './components/App.vue';

import Missions from './components/mission/Library.vue';
import Mission from './components/mission/Index.vue';

Vue.component('grid', require('./components/Grid.vue'));
Vue.component('grid-child', require('./components/GridChild.vue'));

const app = new Vue({
    el: '#app',

    render: h => h(App),

    data: {
        progress: null
    },

    router: new VueRouter({
        mode: 'history',
        routes: [
            { name: 'index', path: '/hub', component: Missions },
            { name: 'library', path: '/hub/missions', component: Missions },
            { name: 'mission', path: '/hub/missions/:ref', component: Mission },
            { name: 'mission-tab', path: '/hub/missions/:ref/:tab', component: Mission },
        ]
    }),

    created() {
        this.$router.beforeEach((to, from, next) => {
            if (from.name === 'mission' && to.component !== 'mission') {
                Events.fire('banner', null);
                return next();
            }

            next();
        });
    }
});

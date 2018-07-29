require('./bootstrap');
require('./filters');
require('./ui');

Vue.component('grid', require('./components/Grid.vue'));
Vue.component('grid-child', require('./components/GridChild.vue'));
Vue.component('modal', require('./components/helpers/Modal.vue'));

import App from './components/App.vue';

import Missions from './components/mission/Library.vue';
import Mission from './components/mission/Index.vue';
import MissionOverview from './components/mission/Overview.vue';
import MissionBriefing from './components/mission/Briefing.vue';
import MissionMedia from './components/mission/Media.vue';
import MissionSettings from './components/mission/Settings.vue';
import MissionComments from './components/mission/Comments.vue';

const app = new Vue({
    el: '#app',

    render: h => h(App),

    data: {
        progress: null
    },

    router: new VueRouter({
        mode: 'history',
        routes: [
            { path: '/hub', component: Missions },
            { path: '/hub/missions', component: Missions },
            {
                path: '/hub/missions/:ref',
                component: Mission,
                children: [
                    { path: '', component: MissionOverview },
                    { path: 'overview', component: MissionOverview },
                    { path: 'briefing', component: MissionBriefing },
                    { path: 'aar', component: MissionComments },
                    { path: 'media', component: MissionMedia },
                    { path: 'notes', component: MissionComments },
                    { path: 'settings', component: MissionSettings },
                ]
            }
        ]
    }),

    created() {
        this.$router.beforeEach((to, from, next) => {
            if (to.path.split('/').length <= 3) {
                Events.fire('banner', null);
            }

            return next();
        });
    }
});

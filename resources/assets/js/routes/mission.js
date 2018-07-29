import Missions from '../pages/mission/Library.vue'
import Mission from '../pages/mission/Index.vue'
import MissionOverview from '../pages/mission/Overview.vue'
import MissionBriefing from '../pages/mission/Briefing.vue'
import MissionMedia from '../pages/mission/Media.vue'
import MissionSettings from '../pages/mission/Settings.vue'
import MissionComments from '../pages/mission/Comments.vue'

export default [
    { path: '/hub/missions', component: Missions, meta: { auth: true } },
    {
        path: '/hub/missions/:ref',
        component: Mission,
        meta: { auth: true },
        children: [
            { path: '', component: MissionOverview, meta: { auth: true } },
            { path: 'overview', component: MissionOverview, meta: { auth: true } },
            { path: 'briefing', component: MissionBriefing, meta: { auth: true } },
            { path: 'aar', component: MissionComments, meta: { auth: true } },
            { path: 'media', component: MissionMedia, meta: { auth: true } },
            { path: 'notes', component: MissionComments, meta: { auth: true } },
            { path: 'settings', component: MissionSettings, meta: { auth: true } },
        ]
    }
]

<template>
    <div v-if="mission !== null" class="container mx-auto h-full">
        <div :class="{ 'text-white': mission.banner.full.length }" class="w-full text-center mt-16">
            <span class="inline-block w-full text-5xl font-black uppercase tracking-wide">{{ mission.name }}</span>
            <span class="inline-block w-full text-2xl font-light">By {{ mission.user.name }}</span>
        </div>

        <ui-tabs @change="setActiveTab" :active="activeTab" class="mt-16 p-0">
            <template slot="label:aar">
                After-Action Report
            </template>

            <template slot="overview">
                <mission-overview :mission="mission"></mission-overview>
            </template>

            <template slot="briefing">
                <mission-briefing :mission="mission"></mission-briefing>
            </template>

            <template slot="aar">
                <mission-comments :mission="mission"></mission-comments>
            </template>

            <template slot="media">
                <mission-media :mission="mission"></mission-media>
            </template>

            <template slot="notes">
                <mission-comments placeholder="Write a note..." collection="notes" :mission="mission"></mission-comments>
            </template>

            <template slot="settings">
                <mission-settings :mission="mission"></mission-settings>
            </template>
        </ui-tabs>
    </div>
</template>

<script>
    import MissionComments from './Comments.vue';
    import MissionOverview from './Overview.vue';
    import MissionBriefing from './Briefing.vue';
    import MissionMedia from './Media.vue';
    import MissionSettings from './Settings.vue';

    export default {
        props: {
            //
        },

        components: {
            'mission-comments': MissionComments,
            'mission-overview': MissionOverview,
            'mission-briefing': MissionBriefing,
            'mission-media': MissionMedia,
            'mission-settings': MissionSettings,
        },

        data() {
            return {
                mission: null,
                activeTab: 'overview',
            }
        },

        computed: {
            routes() {
                return _.map([
                    { name: 'Overview', path: 'overview' },
                    { name: 'Briefing', path: 'briefing' },
                    { name: 'After-Action Report', path: 'aar' },
                    { name: 'Media', path: 'media' },
                    { name: 'Notes', path: 'notes' },
                    { name: 'Settings', path: 'settings' },
                ], r => {
                    r.fullPath = `/hub/missions/${this.mission.ref}/${r.path}`
                    return r
                })
            }
        },

        watch: {
            $route(to, from) {
                let path = to.path.split('/').pop()
                let route = _.find(this.routes, ['path', path])

                if (route) {
                    this.activeTab = route.path
                }
            }
        },

        methods: {
            fetch() {
                return ajax.get(`/api/mission/${this.$route.params.ref}`)
                    .then(r => this.mission = r.data)
                    .then(r => {
                        if (this.mission.banner) {
                            Events.fire('banner', this.mission.banner.full)
                        }
                    })
            },

            setActiveTab(slot) {
                this.activeTab = slot
                this.$router.push(`/hub/missions/${this.mission.ref}/${slot}`)
            }
        },

        created() {
            this.fetch().then(r => {
                let path = this.$route.path.split('/').pop()
                let route = _.find(this.routes, ['path', path])
                this.activeTab = route ? route.path : this.routes[0].path

                if (this.mission.banner) {
                    Events.fire('banner', this.mission.banner.full)
                }
            })

            Events.listen('fetch-mission', this.fetch)
        }
    }
</script>

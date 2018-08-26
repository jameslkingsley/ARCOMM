<template>
    <div v-if="$store.state.mission.viewing !== null" class="max-w-screen-xl mx-auto h-full">
        <div :class="{ 'text-white': $store.state.mission.viewing.banner.full.length }" class="w-full text-center mt-16">
            <span class="inline-block w-full text-5xl font-black uppercase tracking-wide">{{ $store.state.mission.viewing.name }}</span>
            <span class="inline-block w-full text-2xl font-bold">By {{ $store.state.mission.viewing.user.name }}</span>
        </div>

        <ui-tabs @change="setActiveTab" :active="activeTab" class="mt-16 p-0">
            <template slot="label:aar">
                After-Action Report
            </template>

            <template slot="overview">
                <mission-overview></mission-overview>
            </template>

            <template slot="briefing">
                <mission-briefing></mission-briefing>
            </template>

            <template slot="aar">
                <mission-comments :mission="$store.state.mission.viewing"></mission-comments>
            </template>

            <template slot="media">
                <mission-media></mission-media>
            </template>

            <template slot="notes">
                <mission-comments placeholder="Write a note..." collection="notes" :mission="$store.state.mission.viewing"></mission-comments>
            </template>

            <template v-if="$store.state.mission.viewing.actions.update" slot="settings">
                <mission-settings></mission-settings>
            </template>
        </ui-tabs>
    </div>
</template>

<script>
    import MissionMedia from './Media.vue';
    import MissionComments from './Comments.vue';
    import MissionOverview from './Overview.vue';
    import MissionBriefing from './Briefing.vue';
    import MissionSettings from './Settings.vue';

    export default {
        props: {
            //
        },

        components: {
            MissionMedia,
            MissionComments,
            MissionOverview,
            MissionBriefing,
            MissionSettings,
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
                    r.fullPath = `/hub/missions/${this.$store.state.mission.viewing.ref}/${r.path}`
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
            setActiveTab(slot) {
                this.activeTab = slot
                this.$router.push(`/hub/missions/${this.$store.state.mission.viewing.ref}/${slot}`)
            },

            fetch() {
                return this.$store.dispatch('mission/view', this.$route.params.ref)
            }
        },

        created() {
            this.fetch()
                .then(r => {
                    let path = this.$route.path.split('/').pop()
                    let route = _.find(this.routes, ['path', path])
                    this.activeTab = route ? route.path : this.routes[0].path

                    if (this.$store.state.mission.viewing.banner) {
                        Events.fire('banner', this.$store.state.mission.viewing.banner.full)
                    }
                })

            Events.listen('fetch-mission', this.fetch)
        }
    }
</script>

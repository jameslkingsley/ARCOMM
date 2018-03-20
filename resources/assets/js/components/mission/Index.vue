<template>
    <div v-if="mission !== null" class="container mx-auto h-full">
        <div :class="{ 'text-white': mission.banner }" class="w-full text-center mt-16">
            <span class="inline-block w-full text-4xl font-bold uppercase">{{ mission.name }}</span>
            <span class="inline-block w-full text-lg font-semibold uppercase mt-2">By {{ mission.user.name }}</span>
        </div>

        <div class="bg-off-white h-full rounded-t shadow mt-12">
            <div class="bg-white inline-block w-full h-16 rounded-t border-b text-center">
                <router-link
                    :key="index"
                    v-text="route.name"
                    v-for="(route, index) in routes"
                    :class="{ 'active': selected.path === route.path }"
                    :to="route.path"
                    class="mission-tab inline-block text-center font-semibold px-8 py-6 uppercase text-sm leading-none h-full">
                </router-link>
            </div>

            <div class="inline-block w-full p-8">
                <router-view :mission="mission"></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import MissionAAR from './AAR.vue';
    import MissionOverview from './Overview.vue';
    import MissionBriefing from './Briefing.vue';

    export default {
        props: {
            //
        },

        components: {
            'mission-aar': MissionAAR,
            'mission-overview': MissionOverview,
            'mission-briefing': MissionBriefing
        },

        data() {
            return {
                mission: null,
                selected: optional()
            };
        },

        computed: {
            routes() {
                return _.map([
                    { name: 'Overview', path: 'overview' },
                    { name: 'Briefing', path: 'briefing' },
                    { name: 'After-Action Report', path: 'aar' },
                    { name: 'Media', path: 'media' },
                    // { name: 'Addons', path: 'addons' },
                    // { name: 'Notes', path: 'notes' },
                    { name: 'Settings', path: 'settings' },
                ], r => {
                    r.path = `/hub/missions/${this.mission.ref}/${r.path}`;
                    return r;
                });
            }
        },

        watch: {
            $route(to, from) {
                let route = _.find(this.routes, ['path', to.path]);

                if (route) {
                    this.selected = route;
                }
            }
        },

        methods: {
            fetch() {
                return ajax.get(`/api/mission/${this.$route.params.ref}`)
                    .then(r => this.mission = r.data)
                    .then(r => {
                        if (this.mission.banner) {
                            Events.fire('banner', this.mission.banner.full);
                        }
                    });
            }
        },

        created() {
            this.fetch().then(r => {
                let route = _.find(this.routes, ['path', this.$route.path]);
                this.selected = route ? route : this.routes[0];

                if (this.mission.banner) {
                    Events.fire('banner', this.mission.banner.full);
                }
            });

            Events.listen('fetch-mission', this.fetch);
        }
    };
</script>

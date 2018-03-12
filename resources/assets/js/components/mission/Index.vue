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
                    v-text="tab.text"
                    v-for="(tab, index) in tabs"
                    :class="{ 'active': activeTab === tab.uri }"
                    :to="`/hub/missions/${mission.ref}/${tab.uri}`"
                    class="mission-tab inline-block text-center font-semibold px-8 py-6 uppercase text-sm leading-none h-full">
                </router-link>
            </div>

            <div class="inline-block w-full p-8">
                <component :is="`mission-${activeTab}`" :mission="mission"></component>
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
                activeTab: 'overview'
            };
        },

        computed: {
            tabs() {
                return [
                    { text: 'Overview', uri: 'overview' },
                    { text: 'Briefing', uri: 'briefing' },
                    { text: 'After-Action Report', uri: 'aar' },
                    { text: 'Media', uri: 'media' },
                    { text: 'Addons', uri: 'addons' },
                    { text: 'Notes', uri: 'notes' },
                ];
            }
        },

        watch: {
            $route(to, from) {
                if (to.params.ref === from.params.ref) {
                    return this.activeTab = to.params.tab;
                }

                this.fetch(to.params.ref).then(r => {
                    this.activeTab = to.params.tab;
                });
            }
        },

        methods: {
            fetch(ref) {
                return ajax.get(`/api/mission/${ref || this.$route.params.ref}`)
                    .then(r => this.mission = r.data);
            }
        },

        created() {
            this.fetch();

            if ('tab' in this.$route.params) {
                this.activeTab = this.$route.params.tab;
            }

            // Events.fire('banner', '');
        }
    };
</script>

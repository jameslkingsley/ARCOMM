<template>
    <router-link :to="url" class="mission" :style="background">
        <div class="text-left absolute pin-b pin-l pin-r text-white py-6 px-8 mb-3 rounded-b">
            <span class="font-bold text-2xl w-full inline-block mb-1">{{ mission.name }}</span>
            <span class="w-full inline-block opacity-75 mb-1">{{ mission.summary }}</span>
            <span class="w-full inline-block opacity-50 text-sm">{{ mission.mode | capitalize }} &middot; {{ mission.map.name }} &middot; {{ mission.user.name }}</span>
        </div>

        <div role="accent" class="absolute pin-b pin-l pin-r h-4 rounded-b" :class="`bg-${mission.mode}`"></div>
    </router-link>
</template>

<script>
    export default {
        props: {
            mission: Object
        },

        data() {
            return {
                //
            }
        },

        computed: {
            background() {
                return {
                    'background-image': `url(${this.mission.banner.thumb})`
                }
            },

            url() {
                return `/hub/missions/${this.mission.ref}`
            }
        },

        methods: {
            //
        }
    }
</script>

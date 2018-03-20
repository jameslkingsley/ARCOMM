<template>
    <router-link :to="url" class="mission" :style="background">
        <div :class="`bg-${mission.mode}`" class="text-left absolute pin-b pin-l pin-r text-white py-4 px-6 rounded-b">
            <span class="font-semibold w-full inline-block">{{ mission.name }}</span>
            <span class="w-full inline-block opacity-75 text-sm mt-2">{{ mission.summary }}</span>
            <span class="w-full inline-block opacity-50 text-xs mt-2">{{ mission.user.name }} &middot; {{ mission.map.name }}</span>
        </div>
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
            };
        },

        computed: {
            background() {
                return {
                    'background-image': `url(${this.mission.banner.thumb})`
                };
            },

            url() {
                return `/hub/missions/${this.mission.ref}`;
            }
        },

        methods: {
            //
        }
    };
</script>

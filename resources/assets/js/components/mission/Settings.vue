<template>
    <grid template-columns="0.25fr 0.75fr" gap="4rem">
        <div class="text-left">
            <span class="inline-block w-full font-medium">Banner</span>
            <span class="inline-block w-full opacity-50 text-sm">Upload an interesting banner image to showcase your mission.</span>
        </div>

        <div>
            <upload name="banner" :url="bannerUploadUrl" btn-classes="btn-lg" @success="bannerUploaded">
                Choose Image
            </upload>
        </div>

        <div class="text-left">
            <span class="inline-block w-full font-medium">Locked Briefings</span>
            <span class="inline-block w-full opacity-50 text-sm">Lock briefings that contain super top secret information the enemy shouldn't know.</span>
        </div>

        <div>
            <label v-for="(faction, index) in factions" :key="index" class="opacity-100">
                <input type="checkbox" class="float-left mr-2 mt-1" v-model="faction.checked" @change="lockBriefing">
                <span class="text-base font-normal" v-text="faction.name.toUpperCase()"></span>
            </label>
        </div>
    </grid>
</template>

<script>
    import Upload from '../helpers/Upload.vue';

    export default {
        props: {
            mission: Object
        },

        components: {
            Upload
        },

        data() {
            return {
                factions: [
                    { name: 'blufor', checked: false },
                    { name: 'opfor', checked: false },
                    { name: 'indfor', checked: false },
                    { name: 'civilian', checked: false },
                ]
            };
        },

        computed: {
            bannerUploadUrl() {
                return `/api/mission/${this.mission.ref}/banner`;
            }
        },

        methods: {
            bannerUploaded(data) {
                Events.fire('fetch-mission');
            },

            lockBriefing() {
                console.log(this.factions);
            }
        }
    }
</script>

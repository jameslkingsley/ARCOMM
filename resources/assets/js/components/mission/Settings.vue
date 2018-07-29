<template>
    <grid template-columns="0.25fr 0.75fr" gap="8rem" class="p-6">
        <div class="text-left">
            <span class="inline-block w-full font-medium text-xl">Banner</span>
            <span class="inline-block w-full opacity-50">Upload an interesting banner image to showcase your mission.</span>
        </div>

        <div>
            <upload name="banner" :url="bannerUploadUrl" btn-classes="btn-lg" @success="bannerUploaded">
                Choose Image
            </upload>
        </div>

        <div class="text-left">
            <span class="inline-block w-full font-medium text-xl">Locked Briefings</span>
            <span class="inline-block w-full opacity-50">Lock briefings that contain super top secret information the enemy shouldn't know.</span>
        </div>

        <div>
            <div v-for="(faction, index) in factions" :key="index" @click.prevent="lockBriefing(faction)" class="inline-block w-full opacity-100 mb-1 cursor-pointer">
                <i v-show="faction.locked" class="float-left mr-2 material-icons">lock</i>
                <i v-show="!faction.locked" class="float-left mr-2 material-icons">lock_open</i>
                <span class="text-base font-normal" v-text="faction.name.toUpperCase()"></span>
            </div>
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
                    { name: 'blufor', locked: false },
                    { name: 'opfor', locked: false },
                    { name: 'indfor', locked: false },
                    { name: 'civilian', locked: false },
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

            lockBriefing(faction) {
                faction.locked = !faction.locked;
                ajax.put(`/api/mission/${this.mission.ref}/briefing`, { factions: this.factions });
            },

            isLocked(faction) {
                if (this.mission.locked_briefings === null) return false;
                return this.mission.locked_briefings.indexOf(faction.name.toLowerCase()) !== -1;
            }
        },

        created() {
            for (let faction of this.factions) {
                faction.locked = this.isLocked(faction);
            }
        }
    }
</script>

<template>
    <grid template-columns="0.25fr 0.75fr" row-gap="2rem" column-gap="8rem" class="p-6">
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
            <span class="inline-block w-full font-medium text-xl">Hidden Briefings</span>
            <span class="inline-block w-full opacity-50">Hide briefings that contain super top secret information the enemy shouldn't know.</span>
        </div>

        <div>
            <div
                :key="index"
                class="inline-block w-full"
                v-for="(faction, index) in factions">
                <div
                    @click.prevent="lockBriefing(faction)"
                    :class="{ 'opacity-50': faction.locked }"
                    class="float-left cursor-pointer">
                    <ui-icon :name="faction.locked ? 'view-hide' : 'view-show'" class="float-left mr-2" color="grey" size="24" />
                    <span class="text-base font-normal" v-text="faction.name.toUpperCase()"></span>
                </div>
            </div>
        </div>

        <div v-if="mission.actions.verify" class="text-left">
            <span class="inline-block w-full font-medium text-xl">Download</span>
            <span class="inline-block w-full opacity-50">Use the ZIP for testing and the PBO for deploying to the server.</span>
        </div>

        <div v-if="mission.actions.verify">
            <ui-button
                primary
                class="mr-2"
                icon="download"
                @click="download('zip')">
                Zip
            </ui-button>

            <ui-button
                primary
                icon="download"
                @click="download('pbo')">
                PBO
            </ui-button>
        </div>

        <div v-if="mission.actions.verify" class="text-left">
            <span class="inline-block w-full font-medium text-xl">Verification</span>
            <span class="inline-block w-full opacity-50">If the mission is ready to be played then hit that big button. Go ahead.</span>
        </div>

        <div v-if="mission.actions.verify">
            <ui-button
                @click="toggleVerification"
                :primary="!mission.verified_by"
                :success="!!mission.verified_by"
                :icon="mission.verified_by ? '' : 'checkmark'">
                {{ mission.verified_by ? `Verified by ${mission.verified_by.name}` : 'Verify' }}
            </ui-button>
        </div>

        <div v-if="mission.actions.update" class="text-left">
            <span class="inline-block w-full font-medium text-xl">Update</span>
            <span class="inline-block w-full opacity-50">
                You can update your mission, just as long as you
                <router-link class="font-semibold underline" :to="`/hub/missions/${mission.ref}/notes`">provide a change log.</router-link>
            </span>
        </div>

        <div v-if="mission.actions.update">
            <upload name="file" :url="`/api/mission/${mission.ref}`" btn-classes="btn-lg" @success="missionUploaded" @error="missionUploadError">
                Choose PBO File
            </upload>

            <span class="block w-1/2 mt-2 text-error font-medium">
                {{ missionUploadMessage }}
            </span>
        </div>

        <div v-if="mission.actions.delete" class="text-left">
            <span class="inline-block w-full font-medium text-xl">Delete</span>
            <span class="inline-block w-full opacity-50">
                Woah careful, this is the danger zone. You sure you want to delete this? There's no going back.
            </span>
        </div>

        <div v-if="mission.actions.delete">
            <ui-button @click="deleteMission" icon="trash" primary>Delete Mission</ui-button>
        </div>
    </grid>
</template>

<script>
    import Upload from '../../components/helpers/Upload.vue';

    export default {
        components: {
            Upload
        },

        data() {
            return {
                missionUploadMessage: null,
                factions: [
                    { name: 'blufor', locked: false },
                    { name: 'opfor', locked: false },
                    { name: 'indfor', locked: false },
                    { name: 'civilian', locked: false },
                ]
            };
        },

        computed: {
            mission() {
                return this.$store.state.mission.viewing
            },

            bannerUploadUrl() {
                return `/api/mission/${this.mission.ref}/banner`;
            }
        },

        methods: {
            bannerUploaded(data) {
                this.$store.dispatch('mission/view', this.mission.ref)
                    .then(r => {
                        Events.fire('banner', this.$store.state.mission.viewing.banner.full)
                    })
            },

            missionUploaded(data) {
                this.$store.dispatch('mission/view', this.mission.ref)
            },

            missionUploadError(data) {
                this.missionUploadMessage = 'errors' in data ? data.errors.file[0] : data.message
            },

            deleteMission() {
                this.$store.dispatch('mission/destroy', this.mission.ref)
                    .then(r => {
                        this.$router.push('/hub')
                        this.$store.commit('mission/view', null)
                    })
            },

            lockBriefing(faction) {
                faction.locked = !faction.locked;
                ajax.put(`/api/mission/${this.mission.ref}/briefing`, { factions: this.factions });
            },

            isLocked(faction) {
                if (this.mission.locked_briefings === null) return false;
                return this.mission.locked_briefings.indexOf(faction.name.toLowerCase()) !== -1;
            },

            toggleVerification() {
                return this.$store.dispatch('mission/verify', this.mission.ref)
            },

            download(format) {
                this.$store.dispatch('mission/download', {
                    format,
                    ref: this.mission.ref,
                })
            }
        },

        created() {
            for (let faction of this.factions) {
                faction.locked = this.isLocked(faction);
            }
        }
    }
</script>

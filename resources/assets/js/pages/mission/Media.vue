<template>
    <div>
        <div class="bg-off-white-2 border-b border-off-white w-full p-4 text-right">
            <ui-button v-if="mission.actions.update" class="inline-block mr-3" :disabled="!images.length" :icon="manage ? 'checkmark' : 'cog'" @click="manage = !manage">
                {{ manage ? 'Done' : 'Manage'}}
            </ui-button>

            <upload class="inline-block" name="image" :url="imageUploadUrl" @success="fetch" multi>
                Upload Image
            </upload>
        </div>

        <ui-gallery v-if="images.length" :images="images" property="full_url">
            <grid slot-scope="{ open }" class="p-6" template-columns="repeat(4, 0.25fr)" gap="1rem" auto-rows="min-content">
                <div
                    :key="index"
                    @click="manage ? () => {} : open(index)"
                    v-for="(image, index) in images"
                    class="rounded relative bg-center bg-no-repeat bg-cover cursor-pointer"
                    :style="{'background-image': `url(${image.thumb_url})`, 'padding-top': '100%'}">
                    <div v-if="manage" @click="deleteImage(image.id)" class="absolute pin bg-error-50 flex items-center justify-center text-5xl font-bold text-white rounded select-none cursor-pointer">
                        <ui-icon name="trash" size="48" color="white" />
                    </div>
                </div>
            </grid>
        </ui-gallery>

        <div v-else class="flex w-full items-center justify-center py-16 text-lg font-medium text-grey-lighter">
            There's nothing here yet!
        </div>
    </div>
</template>

<script>
    import Upload from '../../components/helpers/Upload.vue';

    export default {
        components: {
            Upload
        },

        data() {
            return {
                images: [],
                manage: false,
            };
        },

        computed: {
            mission() {
                return this.$store.state.mission.viewing
            },

            imageUploadUrl() {
                return `/api/mission/${this.mission.ref}/media`;
            },

            gallery() {
                let result = [];

                for (let image of this.images) {
                    result.push(image.full_url);
                }

                return result;
            }
        },

        methods: {
            fetch() {
                ajax.get(this.imageUploadUrl)
                    .then(r => this.images = r.data);
            },

            deleteImage(id) {
                ajax.delete(`${this.imageUploadUrl}/${id}`)
                    .then(r => {
                        this.images = _.filter(this.images, i => i.id !== id)

                        if (!this.images.length) {
                            this.manage = false
                        }
                    })
            }
        },

        created() {
            this.fetch();
        }
    }
</script>

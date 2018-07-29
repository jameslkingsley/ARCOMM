<template>
    <div class="p-6">
        <upload class="text-right" name="image" :url="imageUploadUrl" :options="galleryOptions" @success="fetch">
            Upload Image
        </upload>

        <gallery :images="gallery" :index="galleryIndex" @close="galleryIndex = null"></gallery>

        <grid class="mt-4" template-columns="repeat(4, 0.25fr)" gap="1rem" auto-rows="min-content">
            <div v-for="(image, index) in images" :key="index" @click="galleryIndex = index" class="rounded relative" :style="{
                'background-image': `url(${image.thumb_url})`,
                'background-position': 'center',
                'background-size': 'cover',
                'background-repeat': 'no-repeat',
                'padding-top': '100%'
            }">
                <button class="absolute pin-t pin-r mt-1 mr-2 text-2xl text-white font-medium" @click.prevent="deleteImage(image.id)">&times;</button>
            </div>
        </grid>
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
                galleryIndex: null,
                galleryOptions: {
                    closeOnSlideClick: true
                }
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
                ajax.get(`/api/mission/${this.mission.ref}/media`)
                    .then(r => this.images = r.data);
            },

            deleteImage(id) {
                //
            }
        },

        created() {
            this.fetch();
        }
    }
</script>

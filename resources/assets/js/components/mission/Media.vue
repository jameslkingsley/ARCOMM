<template>
    <div>
        <upload class="text-right" name="image" :url="imageUploadUrl" @success="fetch">
            Upload Image
        </upload>

        <grid class="mt-4" template-columns="repeat(4, 0.25fr)" gap="1rem" auto-rows="min-content">
            <div v-for="(image, index) in images" :key="index" class="rounded" :style="{
                'background-image': `url(${image.thumb_url})`,
                'background-position': 'center',
                'background-size': 'cover',
                'background-repeat': 'no-repeat',
                'padding-top': '100%'
            }"></div>
        </grid>
    </div>
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
                images: []
            };
        },

        computed: {
            imageUploadUrl() {
                return `/api/mission/${this.mission.ref}/media`;
            }
        },

        methods: {
            fetch() {
                ajax.get(`/api/mission/${this.mission.ref}/media`)
                    .then(r => this.images = r.data);
            }
        },

        created() {
            this.fetch();
        }
    }
</script>

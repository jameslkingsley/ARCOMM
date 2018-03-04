<template>
    <div>
        <button class="btn btn-primary" @click.prevent="chooseFile">Upload</button>

        <form v-show="false">
            <input type="file" name="file" ref="file" @change="upload">
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                file: null
            };
        },

        computed: {
            //
        },

        methods: {
            upload(event) {
                let data = new FormData();
                data.append('file', this.$refs.file.files[0]);

                ajax
                    .post('/api/mission', data, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        onUploadProgress(e) {
                            Events.fire(
                                'progress',
                                Math.floor(e.loaded * 100 / e.total)
                            );
                        }
                    })
                    .then(r => {
                        event.target.value = null;
                        Events.fire('progress', null);
                    })
                    .catch(({ response }) => {
                        event.target.value = null;
                        console.log(response.message);
                    });
            },

            chooseFile() {
                this.$refs.file.click();
            }
        }
    };
</script>

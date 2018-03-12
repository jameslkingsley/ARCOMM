<template>
    <div class="inline-block">
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
                let root = this.$root;
                let data = new FormData();
                data.append('file', this.$refs.file.files[0]);

                ajax
                    .post('/api/mission', data, {
                        headers: { 'Content-Type': 'multipart/form-data' },
                        onUploadProgress(e) {
                            root.progress = Math.floor(e.loaded * 100 / e.total);
                        }
                    })
                    .then(r => {
                        event.target.value = null;

                        this.$router.push({
                            name: 'mission',
                            params: { ref: r.data.ref }
                        });
                    })
                    .catch(({ response }) => {
                        event.target.value = null;
                        console.log(response.data.message);
                    });
            },

            chooseFile() {
                this.$refs.file.click();
            }
        }
    };
</script>

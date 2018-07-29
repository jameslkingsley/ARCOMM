<template>
    <div class="inline-block">
        <ui-button primary @click="chooseFile">Upload</ui-button>

        <form v-show="false">
            <input type="file" name="file" ref="file" @change="upload">
        </form>

        <modal title="Your mission has a fatal error" v-model="hasError" :btn-complete="false" btn-close="Close">
            {{ errorMessage }}
        </modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                file: null,
                hasError: false,
                errorMessage: null
            };
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
                        this.$router.push(`/hub/missions/${r.data.ref}`);
                    })
                    .catch(({ response }) => {
                        event.target.value = null;
                        this.errorMessage = response.data.message;
                        this.hasError = true;
                        console.log(response.data.message);
                    });
            },

            chooseFile() {
                this.$refs.file.click();
            }
        }
    };
</script>

<template>
    <div>
        <ui-button icon="upload" primary :loading="uploading" :class="btnClasses" @click="chooseFile">
            <slot></slot>
        </ui-button>

        <form v-show="false">
            <input type="file" :name="name" ref="file" @change="upload">
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            url: String,
            name: String,
            btnClasses: { type: String, default: '' }
        },

        data() {
            return {
                uploading: false
            }
        },

        methods: {
            upload(event) {
                this.uploading = true

                let root = this.$root;
                let data = new FormData();
                data.append(this.name, this.$refs.file.files[0]);

                ajax
                    .post(this.url, data, {
                        headers: { 'Content-Type': 'multipart/form-data' },
                        onUploadProgress(e) {
                            root.progress = Math.floor(e.loaded * 100 / e.total);
                        }
                    })
                    .then(r => {
                        this.uploading = false
                        event.target.value = null;

                        this.$emit('success', r.data);
                    })
                    .catch(({ response }) => {
                        this.uploading = false
                        event.target.value = null;
                        this.$emit('error', response.data);
                    });
            },

            chooseFile() {
                this.$refs.file.click();
            }
        }
    }
</script>

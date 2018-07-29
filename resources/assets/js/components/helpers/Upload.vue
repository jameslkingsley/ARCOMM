<template>
    <div>
        <ui-button primary :class="btnClasses" @click="chooseFile">
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

        methods: {
            upload(event) {
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
                        event.target.value = null;

                        this.$emit('success', r.data);
                    })
                    .catch(({ response }) => {
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

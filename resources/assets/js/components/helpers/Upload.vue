<template>
    <div>
        <ui-button icon="upload" primary :loading="uploading" :class="btnClasses" @click="chooseFile">
            <slot></slot>
        </ui-button>

        <form v-show="false">
            <input type="file" :name="name" ref="file" @change="upload" :multiple="multi">
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            url: String,
            name: String,
            multi: { type: Boolean, default: false },
            btnClasses: { type: String, default: '' },
        },

        data() {
            return {
                uploading: false
            }
        },

        methods: {
            upload(event) {
                this.uploading = true

                let root = this.$root
                let data = new FormData()
                let index = 0

                for (let file of this.$refs.file.files) {
                    data.append(`${this.name}-${index}`, this.$refs.file.files[index])
                    index++
                }

                ajax
                    .post(this.url, data, {
                        headers: { 'Content-Type': 'multipart/form-data' },
                        onUploadProgress(e) {
                            root.progress = Math.floor(e.loaded * 100 / e.total)
                        }
                    })
                    .then(r => {
                        this.uploading = false
                        event.target.value = null

                        this.$emit('success', r.data)
                    })
                    .catch(({ response }) => {
                        this.uploading = false
                        event.target.value = null
                        this.$emit('error', response.data)
                    })
            },

            chooseFile() {
                this.$refs.file.click()
            }
        }
    }
</script>

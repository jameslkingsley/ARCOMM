<template>
    <div>
        <input v-show="false" @change="setPreview" type="file" :name="name" accept="image/*" ref="file" />
        <slot :open="open" :preview="preview" :previewStyle="previewStyle"></slot>
    </div>
</template>

<script>
    export default {
        props: {
            value: { type: String, default: null },
            name: { type: String, default: 'file' },
        },

        data() {
            return {
                preview: null,
            }
        },

        computed: {
            previewStyle() {
                return `background-image: url(${this.preview ? this.preview : this.value})`
            }
        },

        methods: {
            open() {
                this.$refs.file.click()
            },

            setPreview(event) {
                const input = event.target

                if (input.files && input.files[0]) {
                    let reader = new FileReader()

                    reader.onload = e => {
                        this.preview = e.target.result
                    }

                    reader.readAsDataURL(input.files[0])
                    this.$emit('change', this.getForm())
                }
            },

            getForm() {
                let form = new FormData()
                let file = this.$refs.file.files[0]
                form.append(this.name, file)
                return form
            }
        }
    }
</script>

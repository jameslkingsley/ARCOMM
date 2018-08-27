<template>
    <div>
        <div @click="close" v-show="show" class="fixed pin bg-modal z-40"></div>

        <div
            v-show="show"
            class="fixed z-50 w-image h-image bg-black bg-cover bg-no-repeat bg-center rounded shadow-lg"
            :style="{
                'top': '12vh',
                'left': '15vw',
                'background-image': `url('${image}')`
            }">
            <div @click="prev" class="absolute pin-t pin-l pin-b w-1/5 cursor-pointer"></div>
            <div @click="next" class="absolute pin-t pin-r pin-b w-4/5 cursor-pointer"></div>
        </div>

        <slot :open="open"></slot>
    </div>
</template>

<script>
    export default {
        props: {
            images: Array,
            property: String,
        },

        data() {
            return {
                active: 0,
                show: false,
            }
        },

        computed: {
            image() {
                return this.images[this.active][this.property]
            }
        },

        methods: {
            open(index) {
                this.show = true
                this.active = index
            },

            close() {
                this.show = false
            },

            next() {
                this.active = Math.min(this.active + 1, this.images.length - 1)
            },

            prev() {
                this.active = Math.max(this.active - 1, 0)
            }
        }
    }
</script>

<template>
    <transition name="fade">
        <div class="fixed pin modal-bg z-50" v-show="value">
            <transition name="slide-fade">
                <div :style="{ 'width': '33.33333vw' }" class="mx-auto mt-12 bg-white text-black rounded modal-shadow" v-show="value">
                    <div class="border-b border-off-white py-4 px-6 font-semibold text-sm uppercase rounded-t text-left">
                        {{ title }}
                    </div>

                    <div class="p-6 text-lg text-left normal-case">
                        <slot></slot>
                    </div>

                    <div class="py-4 px-6 border-t border-off-white rounded-b text-right">
                        <button v-if="btnClose !== false" class="text-grey font-semibold uppercase no-underline text-xs p-2 pb-1 no-outline" @click.prevent="close">
                            {{ btnClose }}
                        </button>

                        <button v-if="btnComplete !== false" class="btn ml-2" @click.prevent="complete">
                            {{ btnComplete }}
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            title: String,
            value: { type: Boolean, default: false },
            btnClose: { type: [Boolean, String], default: 'Cancel' },
            btnComplete: { type: [Boolean, String], default: 'Complete' }
        },

        data() {
            return {
                //
            };
        },

        methods: {
            complete() {
                this.$emit('complete');
            },

            close() {
                this.$emit('input', false);
            },

            closeByBackdrop() {
                this.$emit('input', false);
            }
        }
    };
</script>

<style scoped lang="scss">
    .modal-bg {
        background-color: rgba(82, 95, 127, 0.25);
    }

    .modal-shadow {
        box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.07),
            0 2px 15px rgba(84, 96, 103, 0.25);
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s ease;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .slide-fade-enter-active {
        transition: all 0.3s ease;
    }

    .slide-fade-leave-active {
        transition: all 0.3s ease;
    }

    .slide-fade-enter,
    .slide-fade-leave-to {
        transform: translateY(-10rem);
        opacity: 0;
    }
</style>

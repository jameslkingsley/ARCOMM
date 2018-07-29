<template>
    <div class="tabs">
        <div class="labels">
            <div
                class="label"
                :key="`label-${index}`"
                @click="iActive = slot; $emit('change', slot)"
                v-for="(slot, index) in slotLabels"
                :class="{ active: isActive(slot) }">
                {{ isCustomLabel(slot) ? '' : label(slot) }}
                <slot v-if="isCustomLabel(slot)" :name="`label:${slot}`" :active="isActive(slot)"></slot>
                <span v-if="isActive(slot)" class="bar"></span>
            </div>
        </div>

        <div v-for="(node, slot) in $slots" :key="`content-${slot}`">
            <div class="content" v-if="isActive(slot)">
                <slot :name="slot">
                    Default Content
                </slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            active: String,
            labels: { type: Object, default: () => null }
        },

        data() {
            return {
                iActive: this.active
            }
        },

        watch: {
            active(value) {
                this.iActive = value
            }
        },

        computed: {
            slotLabels() {
                let labels = []

                for (let key in this.$slots) {
                    if (! _.startsWith(key, 'label:')) {
                        labels.push(key)
                    }
                }

                return labels
            }
        },

        methods: {
            label(slot) {
                if (this.labels && this.labels[slot]) {
                    return this.labels[slot]
                }

                return _.startCase(slot)
            },

            isActive(slot) {
                return slot === this.iActive
            },

            isCustomLabel(slot) {
                for (let key in this.$slots) {
                    if (_.startsWith(key, 'label:') && key.substr(6).toLowerCase() === slot.toLowerCase()) {
                        return true
                    }
                }

                for (let key in this.$scopedSlots) {
                    if (_.startsWith(key, 'label:') && key.substr(6).toLowerCase() === slot.toLowerCase()) {
                        return true
                    }
                }

                return false
            }
        }
    }
</script>

<style scoped lang="scss">
    .tabs {
        @apply .block .relative .shadow-card .rounded .bg-white;

        .labels {
            @apply .flex .rounded-t;
            border-bottom: 1px solid #e6ebf1;

            .label {
                @apply .relative .flex-1 .bg-off-white-2 .text-grey-light .text-center .py-4 .font-medium .select-none .cursor-pointer .overflow-hidden;
                box-shadow: inset -1px 0 #e6ebf1;
                transition: all 0.15s ease;

                &.active {
                    @apply .bg-white .text-black;
                }

                &:first-of-type {
                    @apply .rounded-tl;
                }

                &:last-of-type {
                    @apply .rounded-tr;
                }

                .bar {
                    @apply .absolute .pin-b .pin-l .pin-r .h-1 .bg-primary;
                }
            }
        }

        .content {
            @apply .p-6 .rounded-b;
        }

        &.p-0 {
            .content {
                @apply p-0;
            }
        }
    }

    .slide-enter-active {
        transition: all 1s ease;
    }

    .slide-leave-active {
        transition: all 01s ease;
    }

    .slide-enter,
    .slide-leave-to {
        opacity: 0;
        // transform: translateX(-100%);
    }
</style>

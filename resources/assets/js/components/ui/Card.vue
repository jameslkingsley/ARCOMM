<template>
    <div :class="classes">
        <span v-if="title" v-text="title" class="w-full block font-medium border-b border-off-white py-4 px-6 text-lg"></span>

        <div v-if="title && !isMultiLevel" :class="{ 'p-6': !noPadding }">
            <slot></slot>
        </div>

        <slot v-if="!title && !isMultiLevel"></slot>

        <div v-if="isMultiLevel" v-for="(slot, key) in slots" :key="key" :ref="key.toLowerCase()" class="w-full" style="flex: 0 0 auto">
            <slot :name="key" :move="move" :title="titleClasses" :back="titleBackBindings"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            value: String,
            title: { type: String, default: null },
            plain: { type: Boolean, default: false },
            outline: { type: Boolean, default: false },
            noPadding: { type: Boolean, default: false },
        },

        data() {
            return {
                titleClasses: {
                    'py-4': true,
                    'px-6': true,
                    'block': true,
                    'w-full': true,
                    'text-lg': true,
                    'border-b': true,
                    'font-medium': true,
                    'border-off-white': true,
                },

                titleBackBindings: {
                    size: '24',
                    name: 'cheveron-left',
                    color: 'grey-lighter',
                    style: 'margin: 1px 5px 0 -10px',
                    class: 'float-left cursor-pointer',
                }
            }
        },

        watch: {
            value(value, old) {
                this.disableInactiveSlides()
                this.$refs[value.toLowerCase()][0].scrollIntoView({
                    block: 'nearest',
                    inline: 'nearest',
                    behavior: 'smooth',
                })
            }
        },

        computed: {
            slots() {
                return Object.assign({}, this.$slots, this.$scopedSlots)
            },

            classes() {
                return {
                    'rounded': true,
                    'relative': true,
                    'border': this.outline,
                    'flex': this.isMultiLevel,
                    'border-off-white': this.outline,
                    'flex-no-wrap': this.isMultiLevel,
                    'shadow': !this.outline && !this.plain,
                    'overflow-x-hidden': this.isMultiLevel,
                    'bg-white': !this.outline && !this.plain,
                    'p-6': !this.title && !this.isMultiLevel && !this.plain,
                }
            },

            isMultiLevel() {
                return Object.keys(this.slots).length > 1
            },

            activeElement() {
                if (!this.isMultiLevel) return

                return this.$refs[this.value.toLowerCase()][0]
            }
        },

        methods: {
            move(slide) {
                this.$emit('input', slide)
            },

            collectTabIndexes() {
                for (let key in this.slots) {
                    let element = this.$refs[key.toLowerCase()][0]
                    let nodes = element.getElementsByTagName('*')

                    for (let node of nodes) {
                        node.setAttribute('data-tabindex', node.getAttribute('tabindex'))
                    }
                }
            },

            disableInactiveSlides() {
                for (let key in this.slots) {
                    let element = this.$refs[key.toLowerCase()][0]
                    let nodes = element.getElementsByTagName('*')

                    for (let node of nodes) {
                        node.setAttribute('tabindex', this.value === key
                            ? node.getAttribute('data-tabindex') : '-1'
                        )
                    }
                }
            }
        },

        mounted() {
            if (!this.isMultiLevel) return

            this.collectTabIndexes()
            this.disableInactiveSlides()

            while (this.$el.scrollLeft !== 0) {
                this.$el.scrollTo(0, 0)
            }

            if (this.activeElement) {
                this.activeElement.scrollIntoView({
                    block: 'nearest',
                    inline: 'nearest'
                })
            }
        }
    }
</script>

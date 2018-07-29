<template>
    <div class="inline-block relative" tabindex="0" @focus="setContext(); focused = true" @blur="close(); focused = false">
        <div v-if="!hasSlot('trigger')" ref="trigger" @click="toggle" class="flex select-none px-4 py-3" :class="triggerClasses">
            <span class="flex-1 text-center text-xs text-black" v-text="selected ? selected : placeholder"></span>
            <ui-icon class="ml-2 self-end" :name="expanded ? 'cheveron-up' : 'cheveron-down'" color="grey-light" size="18" />
        </div>

        <slot v-if="hasSlot('trigger')" name="trigger" :toggle="toggle"></slot>

        <transition name="slide-down">
            <div v-show="expanded" :class="listClasses">
                <span
                    :key="key"
                    v-if="options"
                    @click="select(option, key)"
                    v-for="(option, key) in options"
                    :class="{ 'bg-primary-lightest': highlighted === key }"
                    class="dropdown-item block w-full py-2 px-4 hover:bg-primary-lightest text-sm">
                    {{ option }}
                </span>

                <slot v-if="!options"></slot>
            </div>
        </transition>
    </div>
</template>

<script>
    import Keyboard from 'keyboardjs'

    export default {
        props: {
            fromLeft: { type: Boolean, default: false },
            restrict: { type: Boolean, default: false },
            fromRight: { type: Boolean, default: false },
            placeholder: { type: String, default: null },
            options: { type: Object, default: () => null },
            value: { type: [String, Number, Object, Array, Boolean], default: null },
        },

        data() {
            return {
                selected: null,
                focused: false,
                expanded: false,
                highlightedIndex: 0,
            }
        },

        directives: {
            clickOutside: {
                bind: function (el, binding, vnode) {
                    el.event = function (event) {
                        if (!(el == event.target || el.contains(event.target))) {
                            vnode.context[binding.expression](event)
                        }
                    }

                    document.body.addEventListener('click', el.event)
                },

                unbind: function (el) {
                    document.body.removeEventListener('click', el.event)
                }
            }
        },

        computed: {
            wrapperClasses() {
                return {
                    'bg-white': true,
                    'transition': true,
                    'shadow-button': true,
                    'cursor-pointer': true,
                    'active:shadow-button-active': true,
                }
            },

            triggerClasses() {
                return Object.assign({}, this.wrapperClasses, {
                    'rounded': !this.expanded,
                    'rounded-t': this.expanded,
                    'shadow-outline': this.focused && !this.expanded,
                })
            },

            listClasses() {
                return Object.assign({}, this.wrapperClasses, {
                    'z-50': true,
                    'absolute': true,
                    'pin-t-full': true,
                    'rounded': this.hasSlot('trigger'),
                    'rounded-b': !this.hasSlot('trigger'),
                    'pin-l': this.restrict || this.fromLeft,
                    'pin-r': this.restrict || this.fromRight,
                })
            },

            highlighted() {
                return Object.keys(this.options)[this.highlightedIndex]
            }
        },

        methods: {
            setContext(value = true) {
                Keyboard.setContext(value ? `dropdown-${this._uid}` : 'index')
            },

            toggle() {
                this.expanded = !this.expanded
            },

            close() {
                this.expanded = false
            },

            open() {
                this.expanded = true
            },

            select(option, key) {
                this.toggle()
                this.selected = option
                this.$emit('input', option)
            },

            hasSlot(name = 'default') {
                return !! this.$slots[name]
                    || !! this.$scopedSlots[name]
            }
        },

        created() {
            Keyboard.withContext(`dropdown-${this._uid}`, () => {
                Keyboard.bind('enter', null, e => {
                    if (!this.focused) return

                    if (this.expanded) {
                        this.select(this.options[this.highlighted], this.highlighted)
                        this.setContext(true)
                        this.expanded = false
                    } else {
                        this.expanded = true
                    }
                })

                Keyboard.bind('up', e => {
                    e.preventDefault()
                    if (this.options === null) return

                    const index = this.highlightedIndex - 1
                    this.highlightedIndex = Math.max(0, index)
                })

                Keyboard.bind('down', e => {
                    e.preventDefault()
                    if (this.options === null) return

                    const index = this.highlightedIndex + 1
                    this.highlightedIndex = Math.min(Object.keys(this.options).length - 1, index)
                })
            })
        }
    }
</script>

<style scoped lang="scss">
    .dropdown-item:last-of-type {
        @apply .rounded-b;
    }

    .slide-down-enter-active {
        transition: all 0.15s ease;
    }

    .slide-down-leave-active {
        transition: all 0.15s ease;
    }

    .slide-down-enter,
    .slide-down-leave-to {
        opacity: 0;
        transform: translateY(-1rem);
    }
</style>

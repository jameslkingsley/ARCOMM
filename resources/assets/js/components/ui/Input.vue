<template>
    <div class="inline-block" :style="{ width: actualWidth }">
        <input
            :type="type"
            :name="name"
            class="input"
            v-if="isTextBox"
            v-model="iValue"
            :class="inputClasses"
            :placeholder="placeholder"
            :style="{ width: actualWidth }" />
        
        <textarea
            v-if="type === 'textarea'"
            :name="name"
            :rows="rows"
            class="input"
            v-model="iValue"
            :class="inputClasses"
            :placeholder="placeholder"
            :style="{ width: actualWidth }">
        </textarea>

        <div v-if="type === 'checkbox'" class="inline-block w-full" @click="toggleCheckbox">
            <div :class="checkboxClasses">
                <ui-icon v-if="value" name="checkmark" color="white" size="14" />
            </div>

            <span v-if="label" v-text="label" class="float-left text-xs text-grey font-medium select-none cursor-pointer mt-px"></span>
        </div>

        <ui-dropdown
            restrict
            v-model="iValue"
            :options="options"
            :placeholder="placeholder"
            :class="{ 'w-full': grow }"
            v-if="['select', 'dropdown'].includes(type)" />
    </div>
</template>

<script>
    export default {
        props: {
            label: { type: String, default: null },
            grow: { type: Boolean, default: false },
            type: { type: String, default: 'text' },
            name: { type: String, default: null },
            rows: { type: String, default: null },
            width: { type: String, default: 'auto' },
            placeholder: { type: String, default: null },
            options: { type: Object, default: () => {} },
            value: { type: [String, Number, Object, Array, Boolean], default: null },
        },

        data() {
            return {
                iValue: this.value,
                iOptions: this.options,
            }
        },

        watch: {
            iValue(value) {
                this.$emit('input', value)
            },

            value(value) {
                this.iValue = value
            },

            options(value) {
                this.iOptions = value
                this.$emit('input', this.iValue)
            },
        },

        computed: {
            isTextBox() {
                return [
                    'text',
                    'email',
                    'number',
                    'password',
                ].includes(this.type)
            },

            checkboxClasses() {
                return {
                    'w-5': true,
                    'h-5': true,
                    'flex': true,
                    'mr-4': true,
                    'text-xs': true,
                    'rounded': true,
                    'border-2': true,
                    'uppercase': true,
                    'transition': true,
                    'float-left': true,
                    'select-none': true,
                    'items-center': true,
                    'shadow-button': true,
                    'font-semibold': true,
                    'justify-center': true,
                    'cursor-pointer': true,
                    'bg-white': !this.value,
                    'bg-primary': this.value,
                    'border-primary': !this.value,
                    'border-transparent': this.value,
                }
            },

            inputClasses() {
                return {
                    'transition': true,
                    'px-4': this.isTextBox,
                    'py-3': this.isTextBox,
                    'py-3': this.type === 'textarea',
                    'px-4': this.type === 'textarea',
                    'transition-none': this.type === 'textarea',
                }
            },

            actualWidth() {
                if (this.grow) {
                    return '100%'
                }

                return this.width
            }
        },

        methods: {
            toggleCheckbox() {
                this.$emit('input', !this.value)
            }
        }
    }
</script>

<style scoped lang="scss">
    .input {
        @apply .rounded .text-xs .text-left .shadow-button;

        &:focus {
            @apply .shadow-outline;
        }
    }

    .text-lg {
        .input {
            font-size: inherit !important;
        }
    }
</style>

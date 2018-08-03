<template>
    <button @click="click" :disabled="disabled" :class="classes">
        <i v-if="loading" :class="loaderClasses"></i>

        <ui-icon v-if="success" name="checkmark" :size="iconSize" color="white" :class="iconClasses" />

        <ui-icon v-if="icon && !loading" :name="icon" :size="iconSize" :color="isColored ? 'white' : 'black'" :class="iconClasses" />

        <span :class="textClasses">
            <slot></slot>
        </span>
    </button>
</template>

<script>
    export default {
        props: {
            icon: { type: String, default: null },
            dark: { type: Boolean, default: false },
            text: { type: Boolean, default: false },
            small: { type: Boolean, default: false },
            large: { type: Boolean, default: false },
            error: { type: Boolean, default: false },
            prevent: { type: Boolean, default: true },
            loading: { type: Boolean, default: false },
            primary: { type: Boolean, default: false },
            success: { type: Boolean, default: false },
            disabled: { type: Boolean, default: false },
            secondary: { type: Boolean, default: false },
        },

        data() {
            return {
                //
            }
        },

        computed: {
            isColored() {
                return [
                    this.dark,
                    this.error,
                    this.primary,
                    this.success,
                    this.secondary,
                ].includes(true)
            },

            iconSize() {
                return this.large ? '16' : this.small ? '12' : '14'
            },

            classes() {
                return {
                    'btn': true,
                    'relative': true,
                    'transition': true,
                    'btn-text': this.text,
                    'btn-dark': this.dark,
                    'btn-small': this.small,
                    'btn-large': this.large,
                    'btn-error': this.error,
                    'btn-primary': this.primary,
                    'btn-success': this.success,
                    'btn-loading': this.loading,
                    'btn-disabled': this.disabled,
                    'btn-secondary': this.secondary,
                }
            },

            loaderClasses() {
                return {
                    'loader': true,
                    'small': this.small,
                    'large': this.large,
                    'primary': this.primary,
                    'success': this.success,
                    'secondary': this.secondary,
                }
            },

            iconClasses() {
                return {
                    'mr-3': true,
                    'float-left': true,
                }
            },

            textClasses() {
                return {
                    'opacity-0': this.loading
                }
            }
        },

        methods: {
            click(event) {
                if (this.loading) return

                if (this.prevent) {
                    event.preventDefault()
                }

                this.$emit('click', event)
            }
        }
    }
</script>

<style scoped lang="scss">
    .loader {
        width: 20px;
        height: 20px;
        opacity: 0.33;
        font-size: 10px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.25);
        text-indent: -9999em;
        background: linear-gradient(
            to right,
            rgba(0, 0, 0, 0.75) 10%,
            rgba(255, 255, 255, 0) 42%
        );
        position: absolute;
        top: calc(50% - 10px);
        left: calc(50% - 10px);
        transform: translateZ(0);
        animation: load 0.75s infinite linear;

        &.large {
            width: 24px;
            height: 24px;
            top: calc(50% - 12px);
            left: calc(50% - 12px);
        }

        &.small {
            width: 14px;
            height: 14px;
            top: calc(50% - 7px);
            top: calc(50% - 7px);
            left: calc(50% - 7px);
            left: calc(50% - 7px);
        }

        &.primary {
            opacity: 1;
            background: white;
            background: linear-gradient(
                to right,
                white 10%,
                rgba(255, 255, 255, 0) 42%
            );

            &:before {
                background: white;
            }

            &:after {
                background: config('colors.primary');
            }
        }
    }

    .loader:before {
        top: 0;
        left: 0;
        width: 50%;
        height: 50%;
        content: '';
        position: absolute;
        background: rgba(0, 0, 0, 0.25);
        border-radius: 100% 0 0 0;
    }

    .loader:after {
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 75%;
        height: 75%;
        content: '';
        margin: auto;
        border-radius: 50%;
        position: absolute;
        background: white;
    }

    @-webkit-keyframes load {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes load {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>

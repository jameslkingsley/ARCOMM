<template>
    <div class="field" :class="{ error, success }" :style="{ width }">
        <span v-if="label" :class="{ 'mb-1': help, 'mb-2': !help }" class="block w-full leading-normal font-medium text-sm text-grey" v-text="label"></span>
        <p v-if="help" v-html="help" class="block w-full mb-2 text-xs text-grey-light leading-normal"></p>

        <slot></slot>

        <p v-if="error" v-html="error" class="block w-full text-xs text-error leading-normal mt-2 font-medium"></p>
        <p v-if="success" v-html="success" class="block w-full text-xs text-success leading-normal mt-2 font-medium"></p>
    </div>
</template>

<script>
    export default {
        props: {
            label: String,
            help: { type: String, default: null },
            error: { type: String, default: null },
            success: { type: String, default: null },
            width: { type: String, default: 'auto' },
        },

        data() {
            return {
                //
            }
        },

        computed: {
            //
        },

        methods: {
            //
        }
    }
</script>

<style lang="scss">
    .field {
        @apply .inline-block;

        &.error {
            input {
                box-shadow: 0 0 0 3px config('colors.error-lighter') !important;
            }
        }

        &.success {
            input {
                box-shadow: 0 0 0 3px config('colors.success-lighter') !important;
            }
        }
    }
</style>

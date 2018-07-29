<template>
    <div class="block w-full">
        <div v-for="(item, index) in items" :key="index" class="flex items-center w-full py-1">
            <ui-icon :name="icon" :color="color" size="16" class="mr-3" />
            <span v-html="item" class="flex-1 text-sm font-medium text-grey"></span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            items: Array,
            color: { type: String, default: 'primary' },
            icon: { type: String, default: 'checkmark' },
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

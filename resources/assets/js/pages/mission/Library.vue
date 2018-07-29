<template>
    <div>
        <grid template-columns="repeat(6, 16.66666fr)" gap="1rem">
            <mission-item
                v-for="(mission, index) in missions"
                :key="index"
                :mission="mission">
            </mission-item>
        </grid>
    </div>
</template>

<script>
    import Item from './Item.vue'

    export default {
        props: {
            //
        },

        components: {
            'mission-item': Item
        },

        data() {
            return {
                //
            }
        },

        computed: {
            missions() {
                return this.$store.state.mission.all
            }
        },

        methods: {
            fetch() {
                return this.$store.dispatch('mission/fetch')
            }
        },

        created() {
            this.fetch()
        }
    }
</script>

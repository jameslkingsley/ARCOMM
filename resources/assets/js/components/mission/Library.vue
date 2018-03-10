<template>
    <div>
        <grid template-columns="repeat(6, 16.66666fr)" gap="2rem">
            <mission-item
                v-for="(mission, index) in missions"
                :key="index"
                :mission="mission">
            </mission-item>
        </grid>
    </div>
</template>

<script>
    import Item from './Item.vue';

    export default {
        props: {
            //
        },

        components: {
            'mission-item': Item
        },

        data() {
            return {
                missions: []
            };
        },

        computed: {
            //
        },

        methods: {
            fetch() {
                return ajax.get('/api/mission')
                    .then(r => (this.missions = r.data));
            }
        },

        created() {
            this.fetch();
        }
    };
</script>

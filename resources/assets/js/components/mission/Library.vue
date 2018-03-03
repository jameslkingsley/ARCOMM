<template>
    <div>
        <grid doubles gap="2rem">
            <grid thirds gap="2rem">
                <mission-item>Mission</mission-item>
                <mission-item>Mission</mission-item>
                <mission-item>Mission</mission-item>
            </grid>

            <grid thirds gap="2rem">
                <mission-item>Mission</mission-item>
                <mission-item>Mission</mission-item>
                <mission-item>Mission</mission-item>
            </grid>
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
                return ajax.get('/api/mission').then(r => (this.missions = r.data));
            }
        },

        created() {
            //
        }
    };
</script>

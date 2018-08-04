<template>
    <div class="library">
        <mission-item
            :key="index"
            :mission="mission"
            v-for="(mission, index) in missions">
        </mission-item>
    </div>
</template>

<script>
    import Item from './Item.vue'

    export default {
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

<style lang="scss">
    .library {
        display: grid;
        grid-gap: 1rem;
        grid-template-columns: 1fr;

        [role='name'] {
            @apply text-2xl;
        }

        [role='summary'] {
            @apply inline-block text-base;
        }

        [role='meta'] {
            @apply text-base;
        }

        @screen sm {
            grid-template-columns: repeat(2, 1fr);

            [role='name'] {
                @apply text-lg;
            }

            [role='summary'] {
                @apply inline-block text-sm;
            }

            [role='meta'] {
                @apply text-sm;
            }
        }

        @screen lg {
            grid-template-columns: repeat(3, 1fr);

            [role='name'] {
                @apply text-lg;
            }

            [role='summary'] {
                @apply inline-block text-base;
            }

            [role='meta'] {
                @apply text-base;
            }
        }

        @screen xl {
            grid-template-columns: repeat(6, 1fr);

            [role='verification'] {
                span {
                    @apply text-xxs px-2;
                }
            }

            [role='wrapper'] {
                @apply px-4 py-6;
            }

            [role='name'] {
                @apply text-lg;
            }

            [role='summary'] {
                @apply hidden;
            }

            [role='meta'] {
                @apply text-xs;
            }
        }

        @screen xxl {
            [role='verification'] {
                span {
                    @apply text-xs px-3;
                }
            }

            [role='wrapper'] {
                @apply px-6 py-8;
            }

            [role='name'] {
                @apply text-lg;
            }

            [role='summary'] {
                @apply inline-block text-base;
            }

            [role='meta'] {
                @apply text-base;
            }
        }

        @screen xxxl {
            [role='verification'] {
                span {
                    @apply text-xs px-3;
                }
            }

            [role='wrapper'] {
                @apply px-6 py-8;
            }

            [role='name'] {
                @apply text-2xl;
            }

            [role='summary'] {
                @apply inline-block text-base;
            }

            [role='meta'] {
                @apply text-base;
            }
        }
    }
</style>

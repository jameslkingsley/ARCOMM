<template>
    <div class="h-full">
        <div v-if="banner" class="z-0 absolute pin-t pin-l pin-r h-golden bg-image shadow-lg" :style="banner">
            <div class="absolute pin bg-black opacity-25"></div>
        </div>

        <header :class="{ 'text-white': banner !== null }" class="z-10 w-full h-16 relative">
            <span class="absolute pin-t pin-l pin-r bg-brand h-1.5"></span>
            <nav class="inline-block w-full p-6 uppercase">
                <grid thirds>
                    <a class="inline-block text-left pl-4" href="/hub">
                        <img class="h-9" src="/images/logo.png">
                    </a>

                    <div class="text-center">
                        <router-link :to="page.url" v-for="(page, index) in pages" :key="index" class="inline-block py-2 px-4 font-semibold">
                            {{ page.text }}
                        </router-link>
                    </div>

                    <div class="text-right">
                        <button class="btn btn-primary mr-4">Upload</button>

                        <a href="/hub" class="inline-block py-2 px-4 font-semibold">
                            {{ user.name }}
                        </a>
                    </div>
                </grid>
            </nav>
        </header>

        <main class="relative z-20 pt-9 px-9">
            <!-- <transition name="fade"> -->
                <router-view></router-view>
            <!-- </transition> -->
        </main>
    </div>
</template>

<script>
    export default {
        name: 'app',

        data() {
            return {
                bannerImage: null,
                user: window.App.user,
                pages: [
                    { text: 'Missions', url: '/hub/missions' },
                    { text: 'Guides', url: '/hub/guides' },
                    { text: 'Tutorials', url: '/hub/tutorials' }
                ]
            };
        },

        computed: {
            banner() {
                if (!this.bannerImage) return null;

                return {
                    'background-image': `url(${this.bannerImage})`
                };
            }
        },

        methods: {
            //
        },

        created() {
            Events.listen('banner', e => (this.bannerImage = e));
        }
    };
</script>

<style scoped lang="scss">
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s ease;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .slide-fade-enter-active {
        transition: all 0.3s ease;
    }

    .slide-fade-leave-active {
        transition: all 0.3s ease;
    }

    .slide-fade-enter,
    .slide-fade-leave-to {
        transform: translateY(-10rem);
        opacity: 0;
    }
</style>

<template>
    <div class="h-screen">
        <header class="absolute pin-t pin-l pin-r">
            <nav class="container mx-auto text-white py-6 flex items-center">
                <div class="text-left">
                    <a class="inline-block text-left pl-4" href="/">
                        <img class="h-9" src="/images/logo_white.png">
                    </a>
                </div>

                <div class="text-right flex-1">
                    <a v-if="!!$store.state.auth.me" href="/hub" class="inline-block py-2 px-4 font-semibold">Hub</a>
                    <a class="inline-block py-2 px-4 font-semibold">Join</a>
                    <a class="inline-block py-2 px-4 font-semibold">Media</a>
                </div>
            </nav>
        </header>

        <div class="h-golden bg-primary text-white flex items-center justify-center" style="padding-bottom: 15%">
            <span>ARCOMM</span>
        </div>

        <ui-card class="container mx-auto h-golden" style="margin-top: -15%">
            /
        </ui-card>
    </div>
</template>

<script>
    export default {
        props: {
            //
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

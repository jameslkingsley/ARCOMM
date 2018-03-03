<template>
    <div class="container mx-auto h-full">
        <div class="w-full text-center text-white mt-16">
            <span class="inline-block w-full text-4xl font-bold uppercase">Mission Name</span>
            <span class="inline-block w-full text-lg font-semibold uppercase mt-2">By Author</span>
        </div>

        <div class="bg-white h-full rounded-t shadow-lg mt-12">
            <div class="inline-block w-full h-16 border-b text-center">
                <router-link class="inline-block text-center font-semibold px-8 py-6 uppercase text-sm" to="/hub/missions/example">Overview</router-link>
                <router-link class="inline-block text-center font-semibold px-8 py-6 uppercase text-sm" to="/hub/missions/example">Briefing</router-link>
                <router-link class="border-b-2 border-brand text-brand inline-block text-center font-semibold px-8 py-6 uppercase text-sm" to="/hub/missions/example">After-Action Report</router-link>
                <router-link class="inline-block text-center font-semibold px-8 py-6 uppercase text-sm" to="/hub/missions/example">Media</router-link>
                <router-link class="inline-block text-center font-semibold px-8 py-6 uppercase text-sm" to="/hub/missions/example">Addons</router-link>
                <router-link class="inline-block text-center font-semibold px-8 py-6 uppercase text-sm" to="/hub/missions/example">Notes</router-link>
            </div>

            <div class="inline-block w-full p-10">
                <grid auto-rows="min-content" gap="2rem">
                    <div>
                        <div class="bg-grey-lightest rounded-xl py-2 pb-6 ml-11">
                            <grid template-columns="5rem 1fr">
                                <div class="-ml-12 rounded-full border-5 border-white w-18 h-18 bg-grey"></div>
                                <span class="-ml-10 py-4 text-lg font-bold">Author</span>
                                <grid-child column-start="1" column-end="3" class="inline-block w-full px-4 pr-9 ml-3 -mt-8">
                                    <p>Etiam id est quis nisi vestibulum molestie. Nunc sit amet consectetur dui. Aliquam vestibulum odio sed urna dapibus laoreet. Nunc nec eros vel tellus ultrices porta a sed nulla. Proin at lorem metus. Integer dignissim mauris sed nisi accumsan sodales. Proin iaculis sollicitudin ipsum, mollis ultricies mauris dignissim convallis. Mauris malesuada cursus arcu, quis hendrerit metus interdum vel. Praesent faucibus ex placerat, porta erat eget, dapibus diam.</p>

                                    <p>Aenean consequat nec purus quis tempus. Praesent finibus ultrices sapien, in pretium libero iaculis sed. In mollis mi non pharetra feugiat. Cras consectetur, enim in venenatis fringilla, elit lacus tempor ipsum, vel condimentum magna ligula et lectus. Maecenas aliquam augue id dolor auctor, et viverra ex hendrerit. Etiam lacinia justo sit amet metus sagittis, eget iaculis erat elementum. Donec elementum ipsum sit amet faucibus malesuada. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec molestie, velit sit amet efficitur ultrices, est tortor fringilla urna, non condimentum dui nisi vitae purus. Nullam auctor sapien sit amet ante ornare, vel porta sapien maximus. Ut pharetra tempus volutpat.</p>
                                </grid-child>
                            </grid>
                        </div>
                    </div>
                </grid>
            </div>
        </div>
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
            };
        },

        computed: {
            //
        },

        methods: {
            //
        },

        created() {
            Events.fire(
                'banner',
                random([
                    'https://arcomm.co.uk/_media/163/20180213011554_1.jpg',
                    'https://arcomm.co.uk/_media/211/20180224014950_1.jpg',
                    'https://arcomm.co.uk/_media/55/20180107234956_1.jpg',
                    'https://arcomm.co.uk/_media/191/20180222184117_1.jpg',
                    'https://arcomm.co.uk/_media/203/20180222232134_1.jpg'
                ])
            );
        }
    };
</script>

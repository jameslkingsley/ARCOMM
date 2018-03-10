<template>
    <div>
        <grid auto-rows="min-content" gap="2rem">
            <div>
                <div class="bg-white shadow-lg rounded-xl py-2 pb-6 ml-11">
                    <grid template-columns="5rem 1fr">
                        <div class="-ml-12 rounded-full border-5 border-off-white w-18 h-18 bg-grey"></div>
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
        }
    }
</script>

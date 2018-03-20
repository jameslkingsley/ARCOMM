<template>
    <div>
        <grid auto-rows="min-content" gap="2rem">
            <div v-for="(comment, index) in comments" :key="index">
                <div class="bg-white shadow-lg rounded-xl py-2 pb-6 ml-11">
                    <grid template-columns="5rem 1fr">
                        <div :style="{
                            'background-image': `url(${comment.user.avatar})`,
                            'background-size': '110%',
                            'background-position': 'center',
                            'background-repeat': 'no-repeat'
                        }" class="-ml-12 rounded-full border-5 border-off-white w-18 h-18"></div>

                        <span class="-ml-10 py-4 text-lg font-bold">{{ comment.user.name }}</span>

                        <grid-child column-start="1" column-end="3" class="inline-block w-full px-4 pr-9 ml-3 -mt-8" v-html="comment.text"></grid-child>
                    </grid>
                </div>
            </div>
        </grid>

        <div class="inline-block w-full mt-8">
            <textarea class="mb-4" v-model="comment.text" placeholder="Text"></textarea>
            <button class="float-right btn btn-lg btn-primary ml-6" @click.prevent="publishComment">Publish</button>
            <button class="float-right btn btn-lg" @click.prevent="saveComment">Save Draft</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            mission: Object
        },

        data() {
            return {
                comments: [],
                comment: {
                    text: null,
                    collection: null,
                }
            };
        },

        computed: {
            //
        },

        methods: {
            fetch() {
                ajax.get(`/api/mission/${this.mission.ref}/comment`)
                    .then(r => this.comments = r.data);
            },

            publishComment() {
                ajax.post(`/api/mission/${this.mission.ref}/comment`, this.comment)
                    .then(this.fetch);
            }
        },

        created() {
            this.fetch();
        }
    }
</script>

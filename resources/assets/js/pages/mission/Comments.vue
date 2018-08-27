<template>
    <div>
        <grid auto-rows="min-content" gap="4rem" class="p-6">
            <ui-card v-if="!!comments.length" no-padding plain v-for="(comment, index) in comments" :key="index">
                <div>
                    <grid template-columns="4.5rem 1fr">
                        <div :style="{
                            'background-image': `url(${comment.user.avatar})`,
                            'background-size': '110%',
                            'background-position': 'center',
                            'background-repeat': 'no-repeat'
                        }" class="rounded-full w-12 h-12"></div>

                        <span class="mt-1 text-xl font-bold">
                            {{ comment.user.name }}

                            <ui-icon
                                size="18"
                                name="trash"
                                color="grey-lighter"
                                v-if="comment.actions.delete"
                                @click.native="destroy(comment)"
                                class="float-right ml-4 mt-1 cursor-pointer hover:opacity-75 select-none transition" />

                            <ui-icon
                                size="18"
                                name="edit-pencil"
                                color="grey-lighter"
                                v-if="comment.actions.update"
                                @click.native="editComment(comment)"
                                class="float-right mt-1 cursor-pointer hover:opacity-75 select-none transition" />
                        </span>

                        <grid-child class="-mt-2 text-lg font-normal" column-start="2" column-end="3" v-html="comment.text"></grid-child>
                    </grid>
                </div>
            </ui-card>

            <ui-card v-if="!comments.length" no-padding plain>
                <div class="text-center pt-8 text-grey-lighter font-medium text-xl">Be the first to comment!</div>
            </ui-card>
        </grid>

        <div v-if="creating" class="inline-block w-full bg-off-white-2 border-t border-off-white p-6 mt-8 rounded-b">
            <ui-input type="textarea" v-model="create.text" :placeholder="placeholder" rows="6" class="mb-4 text-lg" grow />
            <ui-button primary large class="float-right" @click="submit">Publish</ui-button>
        </div>

        <div v-else class="inline-block w-full bg-off-white-2 border-t border-off-white p-6 mt-8 rounded-b">
            <ui-input type="textarea" v-model="update.text" :placeholder="placeholder" rows="6" class="mb-4 text-lg" grow />
            <ui-button primary large class="float-right" @click="save">Save Changes</ui-button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            mission: Object,
            collection: { type: String, default: null },
            placeholder: { type: String, default: 'Your mission experience...' },
        },

        data() {
            return {
                creating: true,

                create: {
                    text: null,
                    collection: this.collection,
                    commentable_type: 'missions',
                    commentable_id: this.mission.id,
                },

                update: {
                    id: null,
                    text: null,
                }
            }
        },

        computed: {
            comments() {
                return this.$store.state.comment.all
            }
        },

        watch: {
            create: {
                deep: true,
                handler(value) {
                    const collection = this.collection || ''

                    localStorage.setItem(
                        `comment-text-${this.mission.ref}-${collection}`,
                        value.text
                    )
                }
            }
        },

        methods: {
            fetch() {
                return this.$store.dispatch('comment/fetch', {
                    collection: this.collection,
                    commentable_type: 'missions',
                    commentable_id: this.mission.id,
                })
            },

            submit() {
                const collection = this.collection || ''

                return this.$store.dispatch('comment/submit', this.create)
                    .then(r => {
                        this.create.text = null
                        localStorage.removeItem(`comment-text-${this.mission.ref}-${collection}`)
                    })
            },

            save() {
                return this.$store.dispatch('comment/save', this.update)
                    .then(r => {
                        this.fetch()
                        this.creating = true
                        this.update.id = null
                        this.update.text = null
                    })
            },

            destroy(comment) {
                return this.$store.dispatch('comment/destroy', comment.id)
                    .then(r => {
                        this.fetch()
                    })
            },

            storedCommentText() {
                const collection = this.collection || ''

                return localStorage.getItem(
                    `comment-text-${this.mission.ref}-${collection}`
                )
            },

            editComment(comment) {
                this.update = {
                    id: comment.id,
                    text: _.unescape(comment.text).replace(/<br\s*[\/]?>/gi, '')
                }

                this.creating = false
            }
        },

        created() {
            this.fetch()

            if (this.storedCommentText() !== 'null') {
                this.create.text = this.storedCommentText()
            }
        }
    }
</script>

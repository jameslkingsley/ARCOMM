<template>
    <div>
        <grid auto-rows="min-content" gap="4rem" class="p-6">
            <ui-card no-padding plain v-for="(comment, index) in comments" :key="index">
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
                            <ui-icon v-if="comment.actions.delete" name="trash" size="18" color="grey-lighter" class="float-right ml-4 mt-1 cursor-pointer hover:opacity-75 select-none transition" />
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
        </grid>

        <div class="inline-block w-full bg-off-white-2 border-t border-off-white p-6 mt-8 rounded-b">
            <ui-input type="textarea" v-model="comment.text" :placeholder="placeholder" rows="6" class="mb-4 text-lg" grow />
            <ui-button primary large class="float-right" @click="submit">
                {{ editing ? 'Save Changes' : 'Publish' }}
            </ui-button>
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
                comments: [],
                editing: this.storedCommentId(),
                comment: {
                    collection: this.collection,
                    text: this.storedCommentText(),
                }
            }
        },

        watch: {
            comment: {
                deep: true,
                handler(value) {
                    const collection = this.collection || ''

                    localStorage.setItem(
                        `comment-text-${this.mission.ref}-${collection}`,
                        value.text
                    )

                    localStorage.setItem(
                        `comment-id-${this.mission.ref}-${collection}`,
                        this.editing
                    )
                }
            }
        },

        methods: {
            fetch() {
                const collection = this.collection ? `?collection=${this.collection}` : ''

                ajax.get(`/api/mission/${this.mission.ref}/comment${collection}`)
                    .then(r => this.comments = r.data)
            },

            submit() {
                const collection = this.collection || ''

                if (this.editing) {
                    return ajax.post(`/api/mission/${this.mission.ref}/comment/${this.editing}`, this.comment)
                        .then(r => {
                            this.fetch()
                            this.editing = null
                            this.comment.text = null
                            localStorage.removeItem(`comment-id-${this.mission.ref}-${collection}`)
                            localStorage.removeItem(`comment-text-${this.mission.ref}-${collection}`)
                        })
                }

                return ajax.post(`/api/mission/${this.mission.ref}/comment`, this.comment)
                    .then(r => {
                        this.fetch()
                        this.comment.text = null
                        localStorage.removeItem(`comment-id-${this.mission.ref}-${collection}`)
                        localStorage.removeItem(`comment-text-${this.mission.ref}-${collection}`)
                    })
            },

            storedCommentId() {
                const collection = this.collection || ''

                return localStorage.getItem(
                    `comment-id-${this.mission.ref}-${collection}`
                )
            },

            storedCommentText() {
                const collection = this.collection || ''

                return localStorage.getItem(
                    `comment-text-${this.mission.ref}-${collection}`
                )
            },

            editComment(comment) {
                this.editing = comment.id
                this.comment.text = comment.text
            }
        },

        created() {
            this.fetch()
        }
    }
</script>

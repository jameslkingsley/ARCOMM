export default {
    namespaced: true,

    state: {
        all: []
    },

    actions: {
        fetch({ commit }, { commentable_type, commentable_id, collection }) {
            commit('fetch', [])

            let queries = [
                `id=${commentable_id}`,
                `type=${commentable_type}`,
                collection ? `collection=${collection}` : '',
            ].join('&')

            return ajax.get(`/api/comment?${queries}`)
                .then(r => commit('fetch', r.data))
        },

        submit({ dispatch }, { commentable_type, commentable_id, text, collection }) {
            return ajax.post(`/api/comment`, {
                text, collection, commentable_type, commentable_id
            }).then(r => dispatch('fetch', {
                commentable_type, commentable_id, collection
            }))
        },

        save({ dispatch }, { id, text }) {
            return ajax.post(`/api/comment/${id}`, { text })
        },

        destroy({ dispatch }, id) {
            return ajax.delete(`/api/comment/${id}`)
        }
    },

    mutations: {
        fetch(state, comments) {
            state.all = comments
        }
    }
}

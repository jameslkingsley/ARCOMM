export default {
    namespaced: true,

    state: {
        all: [],
        viewing: null,
    },

    actions: {
        fetch({ commit }) {
            return ajax.get('/api/mission')
                .then(r => commit('fetch', r.data))
        },

        view({ commit }, ref) {
            return ajax.get(`/api/mission/${ref}`)
                .then(r => commit('view', r.data))
        },

        verify({ state, dispatch }, ref) {
            if (state.viewing.verified_by) {
                return ajax.delete(`/api/mission/${ref}/verification`)
                    .then(r => {
                        dispatch('view', ref)
                    })
            }

            return ajax.post(`/api/mission/${ref}/verification`)
                .then(r => {
                    dispatch('view', ref)
                })
        },

        destroy({ commit }, ref) {
            return ajax.delete(`/api/mission/${ref}`)
        }
    },

    mutations: {
        view(state, mission) {
            state.viewing = mission
        },

        fetch(state, missions) {
            state.all = missions
        }
    }
}

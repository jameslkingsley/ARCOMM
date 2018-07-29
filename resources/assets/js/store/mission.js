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

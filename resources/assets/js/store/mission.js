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
        },

        download({ commit }, { ref, format }) {
            return window.location = `/api/mission/${ref}/download?format=${format}`
        }
    },

    mutations: {
        view(state, mission) {
            state.viewing = mission
        },

        fetch(state, missions) {
            state.all = missions
        }
    },

    getters: {
        factions(state) {
            if (!state.viewing) return []

            let filled = []

            for (let f in state.viewing.cfg.briefing) {
                // Always skip game master briefing
                if (f === 'game_master') continue

                if (!state.viewing.actions.update) {
                    if ((state.viewing.locked_briefings || []).includes(f.toLowerCase())) continue
                }

                let filtered = {}
                let faction = state.viewing.cfg.briefing[f]

                for (let s in faction) {
                    if (faction[s].length) {
                        filtered[s] = faction[s]
                    }
                }

                if (Object.keys(filtered).length) {
                    filled.push({
                        name: f.toLowerCase(),
                        sections: filtered
                    })
                }
            }

            return filled
        }
    }
}

export default {
    namespaced: true,

    state: {
        me: null
    },

    actions: {
        checkLogin({ commit, dispatch }) {
            const accessToken = localStorage.getItem('access_token')

            return new Promise((resolve, reject) => {
                if (!accessToken) {
                    return reject(new Error('No access token stored'))
                }

                ajax.get('/api/user/me')
                    .then(r => {
                        commit('update', r.data)
                        resolve(r)
                    })
                    .catch(e => {
                        localStorage.removeItem('access_token')
                        reject(e.response.data)
                    })
            })
        }
    },

    mutations: {
        update(state, data) {
            state.me = Object.assign(state.me || {}, data)
        }
    }
}

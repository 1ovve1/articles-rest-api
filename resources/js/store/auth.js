import client from "./client.js";

export default {
    namespaced: true,
    state: {
        errors: {},
        user: {},
        authState: false,
    },
    getters: {
        isAuth: (state) => state.authState,
        getUser: (state) => state.user,
        getErrors: (state) => state.errors,
        hasRole: (state) => (roleName) => {
            if (state.authState) {
                return state.user.roles.includes(roleName)
            } else {
                return false
            }
        }
    },
    mutations: {
        SET_USER: (state, payload) => {
            state.user = payload
        },
        SET_AUTH_STATE: (state, authState) => {
            if (authState) {
                client.auth.setToken(state.user.token_access)
                client.auth.setTokenType(state.user.token_type)
            } else {
                client.auth.setToken(   '')
                client.auth.setTokenType('')
            }

            state.authState = authState
        },
        SET_ERRORS: (state, errors) => {
            state.errors = errors
        }
    },
    actions: {
        login: async ({ state, dispatch, getters}, { login_email, password }) => {
            return new Promise((resolve, reject) => {
                client.auth.login(login_email, password)
                    .then(response => {
                        dispatch('authorize', response.data.data)
                        resolve()
                    }).catch(err => {
                        dispatch('errors', err)
                        reject(getters.getErrors)
                })
            })
        },

        register: async ({state, dispatch, getters}, payload) => {
            return new Promise((resolve, reject) => {
                client.auth.register(payload)
                    .then(response => {
                        dispatch('authorize', response.data.data)
                        resolve()
                    }).catch(err => {
                        dispatch('errors', err.response)
                        reject(getters.getErrors)
                })
            })
        },

        authorize: ({ commit }, user) => {
            commit('SET_ERRORS', {})
            commit('SET_USER', user)
            commit('SET_AUTH_STATE', true)
        },

        errors: ({ commit }, errors) => {
            commit('SET_ERRORS', {
                data: errors.response.data,
                status: errors.response.status,
            })
            commit('SET_USER', {})
            commit('SET_AUTH_STATE', false)
        },
    },
}

import client from "./client.js";

export default {
    namespaced: true,
    state: {
        authors: [],
    },
    getters: {
        getAuthors: (state) => state.authors,

    },
    mutations: {
        SET_AUTHORS: (state, authors) => {
            state.authors = authors
        },
    },
    actions: {
        loadAuthors: ({ commit }, page = 0) =>
            client.authors.index(page)
                .then(response => {
                    commit('SET_AUTHORS', response.data)
                }),
        getSingleAuthor: async (_, user_id) => {
            const author = await client.authors.show(user_id)

            return author.data.data;
        },
    },
}

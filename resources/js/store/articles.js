import client from "./client.js";

export default {
    namespaced: true,
    state: {
        articles: [],
    },
    getters: {
        getArticles: (state) => state.articles,

    },
    mutations: {
        SET_ARTICLES: (state, articles) => {
            state.articles = articles
        },
    },
    actions: {
        loadArticles: ({ commit }, page = 0) =>
            client.articles.index(page)
                .then(response => {
                    commit('SET_ARTICLES', response.data)
                }),
        getSingleArticle: async (_, slug) => {
            const article = await client.articles.show(slug)

            return article.data.data;
        },
        getUserArticlesById: async (_, user_id) => {
            const articles = await client.authors.articles.index(user_id);

            return articles.data;
        }
    },
}

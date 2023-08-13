import { createStore } from "vuex";

import auth from "./auth.js";
import articles from "./articles.js";
import authors from './authors.js'

export default createStore({
    state: {},
    getters: {},
    mutations: {},
    actions: {},
    modules: {
        auth, articles, authors
    },
});

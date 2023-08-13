import axios from "axios";
import auth from "./auth.js";

// create axios instance
const apiClient = axios.create({
    baseURL: '/api/v1',
    validateStatus: (status) => status >= 200 && status < 400,
})

// create auth token store
const tokenStore = {
    type: '',
    token: '',

    getToken() {
        return this.token
    },
    setToken(token) {
        this.token = token
    },

    getTokenType() {
        return this.type
    },
    setTokenType(type) {
        this.type = type
    }
}

// use token store to inject auth token in each axios request
apiClient.interceptors.request.use(config => {
    const token = tokenStore.getToken()
    const type = tokenStore.getTokenType()

    return {
        ...config,
        headers: {...config.headers, Authorization: `${type} ${token}`}
    }
})

export default {
    apiClient,
    auth: {
        login: (login_email, password) =>
            apiClient.post('/auth/login',{login_email, password}),
        register: (payload) =>
            apiClient.post('/auth/register', payload),
        user: () =>
            apiClient.get('/auth/'),

        setToken: (token) =>
            tokenStore.setToken(token),
        setTokenType: (type) =>
            tokenStore.setTokenType(type),
    },
    authors: {
        index: () =>
            apiClient.get('/authors/'),
        show: (author_id) =>
            apiClient.get(`/authors/${author_id}`),
        store: (payload) =>
            apiClient.post('/authors/', payload),
        update: (author_id, payload) =>
            apiClient.put(`/authors/${author_id}`, payload),
        delete: (author_id) =>
            apiClient.delete(`/authors/${author_id}`),
        articles: {
            index: (author_id, page = 0) =>
                apiClient.get(`/authors/${author_id}/articles?page=${page}`),
        }
    },

    articles: {
        index: (page = 0) =>
            apiClient.get(`/articles?page=${page}`),
        show: (article_slug) =>
            apiClient.get(`/articles/${article_slug}`),
        store: (payload) =>
            apiClient.post('/articles', payload),
        update: (article_slug, payload) =>
            apiClient.put(`/articles/${article_slug}`, payload),
        delete: (article_slug) =>
            apiClient.delete(`/articles/${article_slug}`),
    },

    rubrics: {
        index: () =>
            apiClient.get('/rubrics'),
        show: (rubric_id) =>
            apiClient.get(`/rubrics/${rubric_id}`),
        store: (payload) =>
            apiClient.post('/rubrics', payload),
        update: (rubric_id, payload) =>
            apiClient.put(`/rubrics/${rubric_id}`, payload),
        delete: (rubric_id) =>
            apiClient.delete(`/rubrics/${rubric_id}`),
    },

    publications: {
        index: () =>
            apiClient.get('/publications'),
        show: (publication_id) =>
            apiClient.get(`/publications/${publication_id}`),
        store: (payload) =>
            apiClient.post('/publications', payload),
        delete: (publication_id) =>
            apiClient.delete(`/publications/${publication_id}`),
    },
}

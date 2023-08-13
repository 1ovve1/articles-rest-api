import './bootstrap'
import { createApp } from 'vue/dist/vue.esm-bundler';

const App = {
    template: '<router-view/>',
}

import store from "./store/index.js"
import router from './routes/index.js'

router.beforeEach((to, from) => {
    if (to.name !== 'login') {
        if (!store.getters['auth/isAuth']) {
            return { name: 'login' }
        }
    } else {
        if (store.getters['auth/isAuth']) {
            return { name: 'home' }
        }
    }
})

createApp(App)
    .use(store)
    .use(router)
    .mount('#app')


import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min.js'

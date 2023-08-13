import Home from './../components/Pages/Home.vue';
import Login from './../components/Pages/Login.vue';
import Publications from './../components/Pages/Publications.vue';
import Articles from './../components/Pages/Articles.vue';
import Rubrics from './../components/Pages/Rubrics.vue';
import Authors from './../components/Pages/Authors.vue';


export default [
    { path: '/', component: Home, name: 'home' },
    { path: '/login', component: Login, name: 'login' },
    { path: '/publications', component: Publications, name: 'publications' },
    { path: '/articles', component: Articles, name: 'articles' },
    { path: '/rubrics', component: Rubrics, name: 'rubrics' },
    { path: '/authors', component: Authors, name: 'authors' },
]

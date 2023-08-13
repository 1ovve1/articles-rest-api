<script>
import HomeLayout from "../Layouts/HomeLayout.vue";
import {mapActions, mapGetters} from "vuex";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import AppArticleCard from "../AppArticleCard.vue";

export default {
    name: "Articles",
    components: {AppArticleCard, HomeLayout, Bootstrap5Pagination},
    data: () => ({
    }),
    computed: {
        ...mapGetters({
            articles: "articles/getArticles",
        })
    },
    methods: {
        ...mapActions({
            loadArticles: 'articles/loadArticles'
        })

    },
    mounted() {
        this.loadArticles();
    }
}
</script>

<template>
    <HomeLayout>

        <div class="row text-center">
            <h1>Articles list</h1>
        </div>
        <div class="row w-100 justify-content-center">
            <div class="row w-75">
                <div class="col-4 mt-5" v-for="article in articles.data" :key="article.id">
                    <AppArticleCard
                        :article="article"
                    />
                </div>
            </div>
            <div class="row w-75 justify-content-center mt-5">
                    <Bootstrap5Pagination class="w-auto" :data="articles" @pagination-change-page="loadArticles"/>
            </div>
        </div>

    </HomeLayout>
</template>

<style scoped>

</style>

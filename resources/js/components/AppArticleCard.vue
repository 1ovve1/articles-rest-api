<script>
import {mapActions} from "vuex";

export default {
    name: "AppArticleCard",
    props: {
        article: { type: Object, required: true}
    },
    data: () => ({
        articleEditable: {},
        author: {},
        readModal: false,
        editModal: false,
        deleteModal: false,
    }),
    computed: {
        authorName() {
            const name = `${this.author.first_name ?? ''} ${this.author.last_name ?? ''} ${this.author.patronymic ?? ''}`

            if (this.author.active ?? true) {
                return name
            } else {
                return `${name} (banned)`
            }
        },
        articleTitle() {
            const title = this.article.title

            if (this.article.active ?? true) {
                return title
            } else {
                return `${title} (hidden)`
            }
        },
    },
    methods: {
        ...mapActions({
            findAuthor: 'authors/getSingleAuthor'
        }),

        readArticle() {
            this.findAuthor(this.article.user_id).then((response) => {
                this.author = response
            })
            this.readModal = true
        },
        editArticle() {
            this.editModal = true
        }
    },
    mounted() {
        this.articleEditable = this.article
    }
}
</script>

<template>
    <div class="card" style="width: 18rem;">
        <img :src="article.image_path" :alt="article.slug" />

        <div class="card-body">
            <h5 class="card-title">{{ articleTitle }}</h5>
            <a href="#" class="btn btn-primary" @click.prevent="readArticle">Read</a>
            <a href="#" class="btn btn-primary" @click.prevent="editArticle">Read</a>
        </div>
    </div>

    <ElDialog v-model="readModal">
        <div class="pl-4">
            <h1>{{ articleTitle }}</h1>
            <p><i>{{ authorName }}</i></p>
        </div>
        <div class="d-flex justify-content-center">
            <img :src="article.image_path" :alt="article.slug">
        </div>
        <div>
            {{ article.content }}}
        </div>
    </ElDialog>

    <ElDialog v-model="editModal">
        <ElForm>
            <ElFormItem label="Title:">
                <ElInput v-model="this.articleEditable.title"/>
            </ElFormItem>
            <ElFormItem label="Content:">
                <ElText v-model="this.articleEditable.content"/>
            </ElFormItem>
        </ElForm>
    </ElDialog>

    <ElDialog v-model="deleteModal">
        <div class="pl-4">
            <h1>{{ articleTitle }}</h1>
            <p><i>{{ authorName }}</i></p>
        </div>
        <div class="d-flex justify-content-center">
            <img :src="article.image_path" :alt="article.slug">
        </div>
        <div>
            {{ article.content }}}
        </div>
    </ElDialog>
</template>

<style scoped>

</style>

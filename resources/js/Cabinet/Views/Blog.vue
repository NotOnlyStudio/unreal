<template>
    <div class="d-flex flex-column" v-if="loaded">
        <div class="d-flex flex-column " style="margin-top: 30px;"><p class="block-title tr-none mb-0">My Blog</p> <button class="btn btn-bordered sm mt-3" @click="artRedirect">+ Add Article</button></div>
        <blog
            v-for="(card, key) in blog"
            :url="'/blog/'+card.alias"
            :card-title="card.title"
            :key = "key"
            :editable="true"
            :author="card.user == null ? 'NONE' : card.user.name "
            :card-id="card.id"
            :author-name = "card.user == null ? 'NONE' : card.user.name"
            :rating-plus="card.likes_count"
            :rating-minus="card.dislikes_count"
            :checked="card.user_bookmarks_count == 1 ? true : false"
            :author-id="card.user == null ? 0 : card.user.id "
            :user-assessment="card.user_assessment"
            :content = "card.content"
        />
        <div class="w-100">
            <pagination :data="laravelData" @pagination-change-page="getBlog">
                <span slot="prev-nav"><img src="/img/icons/arrow.svg" alt="">Previous</span>
                <span slot="next-nav">NEXT <img src="/img/icons/arrow.svg" alt=""></span>
            </pagination>
        </div>
    </div>
</template>

<script>
    const blog = () => import("../../components/BlogCard");
    const pagination = () => import('laravel-vue-pagination')

    export default {
        name: "Blog",
        data(){
            return{
                blog:[],
                loaded: false,
                // user: $cookies.get("user"),
                laravelData: [],
            }
        },

        methods:{
            getBlog(page = 1){
                axios.get("/blog-byuser?page="+page)
                .then(
                    resp => {
                        this.blog = resp.data.data;
                        console.log(resp)
                        this.laravelData = resp.data;
                    }
                )
            },
            artRedirect(){
                document.location.href="/upload-article"
            },
        },
        components:{
            blog,pagination
        },
        props:[
            "serverData"
        ],
        mounted() {
            if(this.serverData){
                this.blog = this.serverData.data
                this.laravelData = this.serverData
            }else{
                this.getBlog();
            }
            this.loaded = true;
        }
    }
</script>

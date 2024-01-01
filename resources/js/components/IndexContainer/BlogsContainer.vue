<template>
    <div v-if="loaded" class="d-flex flex-column">
        <blog-card
            v-for="(card, key) in blogs"
            :url="'/blog/'+card.alias"
            :card-title="card.title"
            :key = "key"
            :author="card.user.name "
            :card-id="card.id"
            :show-user="true"
            :author-name = "card.user == null ? 'NONE' : card.user.name"
            :rating-plus="card.likes_count"
            :rating-minus="card.dislikes_count"
            :checked="card.user_bookmarks_count == 1 ? true : false"
            :author-id="card.user == null ? 0 : card.user.id "
            :user-assessment="card.user_assessment ? card.user_assessment : null"
            :content = "card.content"
            type="App\Models\Blog"
        ></blog-card>
        <pagination :data="laravelData" :limit="4" @pagination-change-page="getResults">
            <span slot="prev-nav"><img src="/img/icons/arrow.svg" style="width: 6px;height: 15px" alt="">Previous</span>
            <span slot="next-nav">NEXT <img src="/img/icons/arrow.svg" style="width: 6px;height: 15px" alt=""></span>
        </pagination>
    </div>
    <div v-else class="d-flex cards w-100 flex-wrap justify-content-between">
        <div class="card card-blog">
            <div class="card-header d-flex justify-content-center">
                <b-skeleton animation="fade" width="50%"></b-skeleton>
            </div>
            <div class="card-body">
                <b-skeleton-img card-img="top" aspect="3:1" width="100%" height="700px"></b-skeleton-img>
            </div>
        </div>
        <div class="card card-blog">
            <div class="card-header d-flex justify-content-center">
                <b-skeleton animation="fade" width="50%"></b-skeleton>
            </div>
            <div class="card-body">
                <b-skeleton-img card-img="top" aspect="3:1" width="100%" height="700px"></b-skeleton-img>
            </div>
        </div>
        <div class="card card-blog">
            <div class="card-header d-flex justify-content-center">
                <b-skeleton animation="fade" width="50%"></b-skeleton>
            </div>
            <div class="card-body">
                <b-skeleton-img card-img="top" aspect="3:1" width="100%" height="700px"></b-skeleton-img>
            </div>
        </div>
    </div>
</template>

<script>
    import blogCard from "../BlogCard"
    import axios from "axios"
    const pagination = () => import('laravel-vue-pagination')

    export default {
        name: "BlogsContainer",
        data(){
            return{
                loaded: false,
                blogs:[],
                laravelData: [],
            }
        },
        components:{
            blogCard,
            pagination
        },
        props:[
            "serverData"
        ],
        methods:{
            getResults(page = 1){
                axios.get("/api/blog?page="+page+"&count=3")
                .then(
                    resp =>{
                        if(resp.data.data.length != 0){
                            this.blogs = resp.data.data;
                            this.loaded = true;
                            this.laravelData = resp.data;
                        }
                    }
                )
            }
        },
        mounted(){
            if(this.serverData){
                this.laravelData = this.serverData
                this.blogs = this.serverData.data
                window.addEventListener('load', () => {
                   setTimeout( () => {
                       this.loaded = true
                   },1500)
                })
            }else{
                this.getResults();
            }

          let regex = /<img.*?src="(.*?)"/;
          this.blogs.map(el => {
                el.content = el.content.substring(0, 500)+'...';
                if (el.content.includes('iframe') )
                {
                  el.content = el.content.replace('iframe', 'img');
                  el.content = el.content.replace('width', '');
                  el.content = el.content.replace('height', '');
                  let src = regex.exec( el.content )[1];
                  let lst = src.substring(src.lastIndexOf('/') + 1);
                  el.content = el.content.replace(src, 'https://i.ytimg.com/vi/'+lst+'/maxresdefault.jpg');
                }
          })
        }

    }
</script>

<style scoped>

</style>

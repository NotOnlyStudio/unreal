
<template>
    <div v-if="loaded" class="d-flex flex-column">
        <div class="d-flex w-100 flex-wrap justify-content-between">
            <blogcard
                v-for="(card, key) in cards"
                :url="'/blog/'+card.id"
                :card-title="card.title"
                :rating-plus="card.likes_count"
                :rating-minus="card.dislikes_count"
                :user-assessment="card.user_assessment"
                :key="key"
                :checked="true"
                :author="card.user == null ? 'NONE' : card.user.name "
                :card-id="card.id"
                :author-name = "card.user == null ? 'NONE' : card.user.name"
                :alt="card.title"
                :content = "card.content"
            />
        </div>
        <div class="w-100">
            <pagination :data="laravelData" @pagination-change-page="getResults">
                <span slot="prev-nav"><img src="/img/icons/arrow.svg" alt="">Previous</span>
                <span slot="next-nav">NEXT <img src="/img/icons/arrow.svg" alt=""></span>
            </pagination>
        </div>
    </div>
</template>


<script>
import axios from "axios"
const blogcard = () => import("../../../components/BlogCard")
const pagination = () => import('laravel-vue-pagination')

export default{
    name:"BookmarksBlog",
    components:{
        blogcard,
        pagination
    },
    data(){
        return{
            cards: [],
            loaded: false,
            laravelData: [],
        }
    },
    props:["serverData"],
    methods:{

         getResults(page = 1) {
                let href = document.location.href
                let index = href.indexOf("?");
                href = index == "" ? "" : href.substring(index+1);
                axios.get('/bookmarks/blog?page=' + page+"&"+href)
                    .then(resp => {
                        if(resp.data.data.length != 0){
                          this.cards = resp.data.blogs;
                          this.laravelData = resp.data;
                          this.loaded = true;
                        }

                    });
            },
    },
    mounted(){
        if(this.serverData){
            this.cards = this.serverData.data
            this.laravelData = this.serverData
            this.loaded = true
        }else{
            this.getResults();
        }
    }
}
</script>

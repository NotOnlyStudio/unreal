
<template>
    <div v-if="loaded" class="d-flex flex-column">
        <div class="d-flex w-100 flex-wrap justify-content-between">
            <galcard
                v-for="(card, key) in cards"
                :alias="card.alias"
                :card-title="card.title"
                :rating-plus="card.likes_count"
                :rating-minus="card.dislikes_count"
                :user-assessment="card.user_assessment"
                :author="card.user == null ? 'NONE' : card.user.name "
                :key="key"
                :checked="true"
                :id="card.id"
                :alt="card.title"
                :image = "$imgRoute+'gallery/user-'+card.user_id+'/'+JSON.parse(card.photos)[0]"
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
const galcard = () => import("../../../components/GalleryCard")
const pagination = () => import('laravel-vue-pagination')

export default{
    name:"BookmarksGallery",
    components:{
        galcard,
        pagination
    },
    data(){
        return{
            cards: [],
            loaded: false,
            laravelData: [],
        }
    },
    methods:{
         getResults(page = 1) {
                let href = document.location.href
                let index = href.indexOf("?");
                href = index == "" ? "" : href.substring(index+1);
                axios.get('/bookmarks/gallery?page=' + page+"&"+href)
                    .then(resp => {
                        console.log(resp)
                        if(resp.data.data.length != 0){
                          this.cards = resp.data.data;
                          this.loaded = true;
                          this.laravelData = resp.data;
                        }

                    });
            },
    },
    props:['serverData'],
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

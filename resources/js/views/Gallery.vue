<template>
    <div v-if="loaded">
        <div class="container"><p class="breadcrumbs"><a href="/" style="color:#b3b3b3;text-decoration: none">UnrealShop</a> > Gallery</p></div>
        <div class="d-flex content-right double-column">
            <div class="container mt-4">
                <p class="block-title tr-none">Gallery</p>
                <div class="d-flex filter-list-wrap justify-content-between">
                    <a href="/upload-art" class="btn btn-bordered sm gallery-btn">+   Add Art</a>
                    <div class="d-flex align-items-center filter-list">
                        <a href="/gallery" class="mx-2">All</a>
                        <b-dropdown v-if="challenges_load && challenges.length != 0"  id="dropdown-1" text="Challenges" class=" sort_item " variant=" mx-0">
                            <b-dropdown-item v-for="(challenge, key) in challenges" :key="key" :href="'/challenge/'+challenge.alias+'/cards'">{{challenge.name}}</b-dropdown-item>
                        </b-dropdown>
                        <b-dropdown id="dropdown-1" v-if="avards.length != 0" class="mx-lg-2 mx-0 sort_item" text="Awards"  >
                            <b-dropdown-item v-for="(avard,key) in avards" :key="key" :href="'/gallery/avards/'+avard.user.id">{{avard.user.name}}</b-dropdown-item>
                        </b-dropdown>
                        <b-dropdown id="dropdown-1" text="Sort by" variant="red">
                            <b-dropdown-item href="?order=new">Newest</b-dropdown-item>
                            <b-dropdown-item href="?order=old">Oldest</b-dropdown-item>
                            <b-dropdown-item href="?order=com">Comments</b-dropdown-item>
                            <b-dropdown-item href="?order=lik">Likes</b-dropdown-item>
                            <b-dropdown-item href="?order=dis">Dislikes</b-dropdown-item>
                        </b-dropdown>
                    </div>

                </div>
                <div class="cards">
                    <galcard
                        v-for="(card, key) in cards"
                        :alias="card.alias"
                        :card-title="card.title"
                        :rating-plus="card.likes_count"
                        :rating-minus="card.dislikes_count"
                        :user-assessment="card.user_assessment"
                        :author="card.user == null ? 'NONE' : card.user.name "
                        :key="key"
                        :checked="card.user_bookmarks_count == 1 ? true : false"
                        :id="card.id"
                        :alt="card.title"
                        :author-id="card.user == null ? 0 : card.user.id"
                        :image = "$imgRoute+'gallery/user-'+card.user_id+'/'+JSON.parse(card.photos)[0]"
                    />
                </div>
                <div class="w-100">
                    <pagination :limit="4" :data="laravelData" @pagination-change-page="getResults">
                        <span slot="prev-nav"><img src="/img/icons/arrow.svg" alt="">Previous</span>
                        <span slot="next-nav">NEXT <img src="/img/icons/arrow.svg" alt=""></span>
                    </pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import galcard from '../components/GalleryCard';
    const pagination = () => import('laravel-vue-pagination')
    import Challenges from "../components/Challenges/ChallengesWrapper";
    import axios from 'axios'
    import { paginationMethods } from "../Mixins/pagination"
import Challenge from '../components/Challenges/Challenge.vue';
    export default {
        name: "Gallery",
        components:{
            Challenges,
            galcard,pagination,
        },
        mixins:[paginationMethods],
        props:[
            "serverData"
        ],
        data(){
             return{
                loaded: false,
                cards:[],
                laravelData: [],
                avards:[],
                challenges:[],
                challenges_load:false,
            }
        },
        methods:{
            getResults(page = 1){
                let href = document.location.href
                let index = href.indexOf("?");
                let params = window.location.href;
                href = index == "" ? "" : href.substring(index+1);
                params = params.replace(/.*page=(\d+).*$/,"")
                var newUrl=this.paginationUrl(window.location.href,"page",page);
                window.history.pushState("", document.title, newUrl);
                axios.get("/gallery-get?page="+page+"&count=9&"+href)
                .then(
                    resp => {

                        if(resp.data.data.length != 0){
                            this.cards = resp.data.data;
                            this.loaded = true;
                            this.laravelData = resp.data;
                        }
                    }
                )
            },
            getAvards(){
                axios.get("/api/avards")
                .then(
                    resp => {
                        this.avards = resp.data.avards
                    }
                )
            }
        },
        mounted(){
            if(this.serverData){
                this.cards = this.serverData.data
                this.laravelData = this.serverData
                this.loaded = true
            }else{
                this.getResults();
            }
            axios.get("/api/challenges")
            .then(
                resp => {
                    this.challenges = resp.data.challenges
                    this.challenges_load = true
                }
            )
            this.getAvards();
            this.$store.dispatch("setChallengeStyles",`top:127px`)

        }
    }
</script>


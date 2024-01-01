<template>
    <div v-if="loaded">
        <div class="container"><p class="breadcrumbs">3drabbit > Gallery</p></div>
        <div class="d-flex content-right double-column">
            <div class="container mt-4">
                <p class="block-title tr-none">Gallery</p>
                <div class="d-flex filter-list-wrap justify-content-between">
                    <a href="/upload-art" class="btn btn-bordered sm gallery-btn">+   Add Art</a>
                    <div class="d-flex align-items-center filter-list">
                        <a href="/gallery" class="mx-2">All</a>
                        <b-dropdown v-if="challenges_load"  id="dropdown-1" text="Challenges" class=" sort_item " variant=" mx-0">
                            <b-dropdown-item v-for="(challenge, key) in challenges" :key="key" :href="'?challenge='+challenge.id">{{challenge.name}}</b-dropdown-item>
                        </b-dropdown>
                        <b-dropdown id="dropdown-1" v-if="avards.length != 0" class=" sort_item" text="Awards"  >
                            <b-dropdown-item v-for="(avard,key) in avards" :key="key" :href="'/gallery/avards/'+avard.user.id" :active="currentAvard == avard.user.id">{{avard.user.name}}</b-dropdown-item>
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
                <div class="d-flex w-100 flex-wrap cards justify-content-between">
                    <galcard
                        v-for="(card, key) in cards"
                        :url="'/gallery/'+card.id"
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
                        :image = "$imgRoute+'gallery/user-'+card.user_id+'/'+card.photos[0]"
                    />
                </div>
                <div class="w-100">
                    <pagination :data="laravelData" @pagination-change-page="getResults">
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
    import Challenge from '../components/Challenges/Challenge.vue';
    export default {
        name: "AvardsPage",
        components:{
            Challenges,
            galcard,pagination,
        },
    
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
        computed:{
            currentAvard(){
                return window.location.pathname.split("/")[3];
            }
        },
        methods:{
            getResults(page = 1){
                let href = document.location.href
                let index = href.indexOf("?");
                href = index == "" ? "" : href.substring(index+1);
                axios.get("/api/gallery?page="+page+"&count=9&"+href)
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
        props:["serverData"],
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
            this.$store.dispatch("setChallengeStyles",`top:20px`)
        },
        // created(){
        //     setTimeout(()=>{
        //         this.challengePosition(20)
        //     },500)
        // }

    }
</script>


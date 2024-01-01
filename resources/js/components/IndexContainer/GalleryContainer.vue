<template>
    <div v-if="loaded">
        <div  class="cards">
            <galcard
                v-for="(card, key) in cards"
                :url="'/gallery/'+card.id"
                :card-title="card.title"
                :rating-plus="card.likes_count"
                :rating-minus="card.dislikes_count"
                :user-assessment="card.user_assessment != null ? card.user_assessment : null"
                :author="card.user  ? card.user.name : 'NONE'"
                :key="key"
                :checked="card.user_bookmarks_count == 1 ? true : false"
                :id="card.id"
                :alt="card.title"
                :alias="card.alias"
                :author-id="card.user == null ? 0 : card.user.id"
                :image = "$imgRoute+'gallery/user-'+card.user_id+'/'+JSON.parse(card.photos)[0]"
            />
        </div>
        <div class="w-100">
                <pagination :limit="4" :data="laravelData" @pagination-change-page="getResults">
                    <span slot="prev-nav"><img src="/img/icons/arrow.svg" style="width: 6px;height: 15px" alt="">Previous</span>
                    <span slot="next-nav">NEXT <img src="/img/icons/arrow.svg" style="width: 6px;height: 15px" alt=""></span>
                </pagination>
            </div>
    </div>

    <div v-else class="d-flex cards w-100 flex-wrap justify-content-between">
        <div class="card card-gallery">
            <b-skeleton class="card-img-top card-skeleton-bg" ></b-skeleton>
            <div class="card-body w-100">
                <div class="d-flex justify-content-center">
                    <b-skeleton class="card-title w-50"></b-skeleton>
                </div>
                <div class="d-flex justify-content-between">
                    <b-skeleton class="w-25"/>
                    <b-skeleton class="w-25"/>
                </div>
            </div>
        </div>
        <div class="card card-gallery">
            <b-skeleton class="card-img-top card-skeleton-bg" ></b-skeleton>
            <div class="card-body w-100">
                <div class="d-flex justify-content-center">
                    <b-skeleton class="card-title w-50"></b-skeleton>
                </div>
                <div class="d-flex justify-content-between">
                    <b-skeleton class="w-25"/>
                    <b-skeleton class="w-25"/>
                </div>
            </div>
        </div>
        <div class="card card-gallery">
            <b-skeleton class="card-img-top card-skeleton-bg" ></b-skeleton>
            <div class="card-body w-100">
                <div class="d-flex justify-content-center">
                    <b-skeleton class="card-title w-50"></b-skeleton>
                </div>
                <div class="d-flex justify-content-between">
                    <b-skeleton class="w-25"/>
                    <b-skeleton class="w-25"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import galcard from '../GalleryCard';
    const pagination = () => import('laravel-vue-pagination')

    import axios from 'axios';
    export default {
        name: "GalleryContainer",
        components:{
            galcard,pagination
        },
        methods:{
            getResults(page = 1){
                axios.get("/gallery-get?page="+page+"&count=3")
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

        },
        props:[
            "serverData"
        ],
        data(){
            return{
                loaded: false,
                cards:[],
                laravelData: [],
            }
        },
        mounted(){
           if(this.serverData){
                this.laravelData = this.serverData
                this.cards = this.serverData.data
                this.loaded = true
            }else{
                this.getResults();
            }
        }
    }
</script>

<style scoped>

</style>

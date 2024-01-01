<template>
    <div  v-if="loaded">
        <div class="cards">
            <ProductCard
                v-for="(card, key)  in cards"
                :url="'/models/'+card.alias"
                :card-title="card.title"
                :key="key"
                :user-id="card.user_id"
                :is-free="card.is_free"
                :is-vr="card.is_vr"
                :id="card.id"
                :checked="card.user_bookmarks_count == 1 ? true : false"
                :images="card.photos | unJson"
                :buyed="card.user_purchases ? card.user_purchases.id : null"
            />
        </div>
         <div class="w-100">
            <pagination :limit="4" :data="laravelData" @pagination-change-page="getResults">
                <span slot="prev-nav"><img src="/img/icons/arrow.svg" style="width: 6px;height: 15px" alt="">Previous</span>
                <span slot="next-nav">NEXT <img src="/img/icons/arrow.svg" style="width: 6px;height: 15px" alt=""></span>
            </pagination>
        </div>
    </div>

    <div v-else class="cards">
        <div class="card card-white">
            <b-skeleton class="card-img-top card-skeleton-bg" ></b-skeleton>
            <div class="card-body w-100">
                <b-skeleton class="card-title w-75"></b-skeleton>
                <b-skeleton  type="button"></b-skeleton>
            </div>
        </div>
        <div class="card card-white">
            <b-skeleton class="card-img-top card-skeleton-bg" ></b-skeleton>
            <div class="card-body w-100">
                <b-skeleton class="card-title w-75"></b-skeleton>
                <b-skeleton  type="button"></b-skeleton>
            </div>
        </div>
        <div class="card card-white">
            <b-skeleton class="card-img-top card-skeleton-bg" ></b-skeleton>
            <div class="card-body w-100">
                <b-skeleton class="card-title w-75"></b-skeleton>
                <b-skeleton  type="button"></b-skeleton>
            </div>
        </div>
        <div class="card card-white">
            <b-skeleton class="card-img-top card-skeleton-bg" ></b-skeleton>
            <div class="card-body w-100">
                <b-skeleton class="card-title w-75"></b-skeleton>
                <b-skeleton  type="button"></b-skeleton>
            </div>
        </div>
        <div class="card card-white">
            <b-skeleton class="card-img-top card-skeleton-bg" ></b-skeleton>
            <div class="card-body w-100">
                <b-skeleton class="card-title w-75"></b-skeleton>
                <b-skeleton  type="button"></b-skeleton>
            </div>
        </div>
        <div class="card card-white">
        <b-skeleton class="card-img-top card-skeleton-bg" ></b-skeleton>
        <div class="card-body w-100">
            <b-skeleton class="card-title w-75"></b-skeleton>
            <b-skeleton  type="button"></b-skeleton>
        </div>
    </div>
    </div>
</template>

<script>

    const ProductCard = () => import("../ProductCard")
    const pagination = () => import('laravel-vue-pagination')
    export default {
        name: "CardsSubblock",
        data(){
            return{
                cards: [],
                loaded: false,
                laravelData: [],
            }
        },
        filters:{
            unJson(data){
                return JSON.parse(data)
            }
        },
        props:[
            "serverData"
        ],
        methods:{
            getResults(page = 1) {
                this.cards = []
                console.log('alsdlfsdlldfl')
                console.log(this.cards)
                window.axios.get('/products-get?page=' + page+"&count=6")
                    .then(resp => {
                        if(resp.data.data.length != 0){
                          this.cards = resp.data.data;
                          console.log(JSON.parse(this.cards[0].photos));
                          this.laravelData = resp.data;
                        }

                    });
            },
        },
        components:{
            ProductCard,
            pagination
        },
        mounted(){
            if(this.serverData){
                this.laravelData = this.serverData
                this.cards = this.serverData.data
                this.loaded = true
            }else{
                this.getResults();
                this.loaded = true;
            }

        }
    }
</script>

<style>
    .card-skeleton-bg{
        width: 100%;
        height: 320px;
    }
    .card .b-skeleton-button{
        margin-top: 12px;
        margin-bottom: 20px;
        width: 165px;
        height: 47px;
    }
</style>

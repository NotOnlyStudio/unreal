
<template>
    <div v-if="loaded" class="d-flex flex-column">
        <div class="d-flex w-100 flex-wrap justify-content-between">
            <product
                v-for="(card, key)  in cards"
                :url="'/models/'+card.alias"
                :cardTitle="card.title"
                :key="key"
                :user-id="card.user_id"
                :id="card.id"
                :checked="true"
                :images="JSON.parse(card.photos)"
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
const product = () => import("../../../components/ProductCard")
const pagination = () => import('laravel-vue-pagination')

export default{
    name:"BookmarksModels",
    components:{
        product,
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
                axios.get('/bookmarks/products?page=' + page+"&"+href)
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
    props:["serverData"],
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

<template>
    <div v-if="loaded" class="d-flex flex-column">
        <div class="d-flex flex-column container px-0" v-if="moderationCards.length != 0">
            <p class="block-title tr-none mb-0 mt-5">Moderation</p>
            <div class="cards" v-if="moderationCards != null && moderationCards.length != 0">
                <moderCard
                    v-for="(card,key) in moderationCards"
                    :key="key"
                    :keygen="key"
                    :card-title="card.title"
                    type="gallery"
                    :user-id="card.user_id"
                    :image="JSON.parse(card.photos)[0]"
                    :id="card.id"
                    @onDelete="onDelete"
                />
            </div>
        </div>

         <pagination :data="moderationData" @pagination-change-page="getModerations">
            <span slot="prev-nav"><img src="/img/icons/arrow.svg" alt="">Previous</span>
            <span slot="next-nav">NEXT <img src="/img/icons/arrow.svg" alt=""></span>
        </pagination>
        <div class="d-flex flex-column " style="margin-top: 30px;"><p class="block-title tr-none mb-0">My Art</p> <button class="btn btn-bordered sm mt-3" @click="artRedirect">+ Add Art</button></div>
        <div v-if="moderatedCards" class="cards">
            <galcard
                v-for="(card, key) in moderatedCards"
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
            <pagination :data="moderatedData" @pagination-change-page="getModerated">
                <span slot="prev-nav"><img src="/img/icons/arrow.svg" alt="">Previous</span>
                <span slot="next-nav">NEXT <img src="/img/icons/arrow.svg" alt=""></span>
            </pagination>
    </div>
    <div v-else>
        <h1>Сделать прелоадер</h1>
    </div>
</template>
<script>
    import axios from "axios"
    const moderCard = () => import("../Components/ModerationCard")
    const pagination = () => import('laravel-vue-pagination')
    import galcard from '../../components/GalleryCard';
    export default {
        name: "Gallery",
        data(){
            return{
                'user':$cookies.get("user"),
                'moderationCards':null,
                'moderatedCards':null,
                'moderatedData':[],
                'moderationData':[],
                'loaded': false,
            }
        },
        components:{
            moderCard,
            pagination,
            galcard
        },
        methods:{
            onDelete(data){
                let key = data.key;
                axios.delete("/gallery/"+this.moderationCards[key].id)
                .then(
                    resp => {
                        if(resp.status == 200){
                            this.moderationCards.splice(key,1)
                        }
                    }
                )
            },
            artRedirect(){
                document.location.href="/upload-art"
            },
            getModerations(page = 1){
                axios.get('/user/moderation/gallery?page='+page)
                .then(
                    resp => {
                        if(resp.status == 200){
                            this.moderationCards = resp.data.data;
                            this.moderationData = resp.data
                        }
                    }
                )
            },
            getModerated(page = 1){
                axios.get('/user/moderated/gallery?page='+page)
                    .then(
                        resp => {
                            if(resp.status == 200 && resp.data.length != 0){
                                this.moderatedCards = resp.data.data;
                                this.moderatedData = resp.data
                            }
                        }
                    )
            }
        },
        props:["serverData"],
        mounted(){
            if(this.serverData){
                console.log(this.serverData)
                this.moderationCards = this.serverData[1].data
                this.moderatedCards = this.serverData[0].data
                this.moderationData = this.serverData[1]
                this.moderatedData = this.serverData[0]
            }else{
                this.getModerations();
                this.getModerated();
            }
            this.loaded = true;
        }
    }
</script>
<style scoped>
</style>

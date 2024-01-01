<template>
    <div v-if="this.loaded">
        <div class="container">
            <p class="breadcrumbs"><a style="color: rgb(179, 179, 179); text-decoration: none;" href="/">UnrealShop</a> > <a style="color: rgb(179, 179, 179); text-decoration: none;" href="/gallery">Gallery</a> > {{item.title.length > 50 ? item.title.substring(0,50)+"..." : item.title}}</p>
        </div>
        <div class="d-flex content-right double-column">
            <div class="container mt-4">
                <div class="d-flex  flex-column">
                    <p class="block-title bold cr tr-none mt-big mt-3 mb-0">{{item.title}}</p>
                   <div class="counts-absolute">
                        <rating
                            :item-id="item.id"
                            type="App\Models\Gallery"
                            :rating-plus="likes_count"
                            :rating-minus="dislikes_count"
                            :assessment="item.user_assessment ? item.user_assessment : null    "
                            :author-id="item.user_id"
                            :item-content="item.title"
                            :uncolumn="true"
                        />
                   </div>

                    <div class="px-lg-0 px-md-0 px-2 images-column position-relative mt-3 d-flex flex-column position-relative">
                        <div class="product__information">
                            <p
                                class="description"
                                v-if="item.description "
                                v-html="description"
                            />
                        </div>
                        <author
                            :name="item.user.name"
                            :photo="item.user.photo"
                            rank="rookie"
                            :id="item.user.id"
                        />
                        <iframe
                                v-for="(video,key) in this.video" :key="'v-'+key"
                                v-if="video != null && video != 'null'"
                                :src="video"
                                frameborder="0"
                                class="w-100 mt-4 "
                                style="height: 500px"
                            />
                        <div class="image" v-for="(img,key) in photos" :key="key">
                            <picture  @click="index = key">
                                <!-- <source type="image/webp" :srcset="$imgRoute+'models/user-' + info.users.id + '/' + img | webpThumb" /> -->
                                <source type="image/png" :srcset="$imgRoute+'gallery/user-' + item.user_id + '/' + img" />
                                <source type="image/jpg" :srcset="$imgRoute+'gallery/user-' + item.user_id + '/' + img" />
                                <source type="image/jpeg" :srcset="$imgRoute+'gallery/user-' + item.user_id + '/' + img" />
                                <b-img-lazy :src="$imgRoute+'gallery/user-'+item.user_id+'/'+img" :alt="`image # ${key}`" />
                            </picture>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <tiny-box v-model="index" :images="tinyBoxImages" />
        <div class="d-flex container flex-column comments">
            <comments type="App\Models\Gallery"
                comments-type="App\Models\Gallery"
                :item-id="item.id"
                :item-user="item.user.id"
            />
        </div>
    </div>
</template>

<script>
    import galimage from "../components/GalleryImg";
    const comments = () => import("../components/Comments/CommentsWrapper");
    const rating = () => import("../components/Rating/RatingWrapper")
    import Challenges from "../components/Challenges/ChallengesWrapper";
    import Tinybox from "vue-tinybox";
    const author = () => import("../components/Author");
    export default {
        name: "GalleryItem",
        data(){
            return{
                item:[],
                photos:[],
                video:[],
                dislikes_count:0,
                likes_count:0,
                index: null,
                loaded: false,
            }
        },
        components:{
            galimage,
            comments,
            author,
            rating,
            Challenges,
            "tiny-box":Tinybox,
        },
        computed:{
            description(){
                let descr = this.item.description
                descr = descr.replace('\n','<br>')
                descr = descr.replace('\t','<br>')
                return descr.replace(/\r?\n/g, '<br />')
            },
            tinyBoxImages(){
                let images = [];
                this.photos.forEach(
                    img => {
                        images.push(`${this.$imgRoute}gallery/user-${this.item.user_id}/${img}`)
                    }
                )
                return images
            }
        },
        methods:{
            getResult(){
                let alias = this.$route.params.alias;
                axios.get("/api/gallery/"+alias).then(resp =>{
                    this.item = resp.data
                    this.photos = JSON.parse(this.item.photos);
                    this.likes_count = this.item.likes_count
                    this.dislikes_count = this.item.dislikes_count
                })
            }
        },
        props:["serverData"],
        mounted(){
            if(this.serverData){
                this.item = this.serverData
                this.photos = JSON.parse(this.item.photos);
                if(this.item.video.indexOf(']')>0) {
                    this.video = JSON.parse(this.item.video);
                }else{
                    this.video.push(this.item.video)
                }
                this.likes_count = this.item.likes_count
                this.dislikes_count = this.item.dislikes_count
           }else{
               this.getResult();
           }
            this.loaded = true
            this.$store.dispatch("setChallengeStyles",`top:103px;`)
        }
    }
</script>


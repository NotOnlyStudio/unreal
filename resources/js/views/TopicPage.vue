<template>
    <div v-if="loaded" class="d-flex flex-column container">
                <p class="breadcrumbs">
        <a href="/" style="color: #b3b3b3; text-decoration: none">UnrealShop</a> >
        <a href="/forum" style="color: #b3b3b3; text-decoration: none">Forum</a> >
        <a :href="'/forum/'+pathName.toLowerCase()" style="color: #b3b3b3; text-decoration: none">{{pathName}}</a> >
        {{title}}
      </p>
        <div class="d-flex container content-right double-column w-100">
            <BlogCard
            :card-title="card.title"
            :author="card.user == null ? 'NONE' : card.user.name"
            :card-id="card.id"
            :author-name="card.user == null ? 'NONE' : card.user.name"
            :rating-plus="card.likes_count"
            :rating-minus="card.dislikes_count"
                :checked="card.user_bookmarks_count == 1 ? true : false"
            :user-assessment="card.user_assessment ? card.user_assessment : card.user_assessment "
            :content="card.content"
                :id="card.id"
            :author-id="card.user_id"
            :show-user="false"
            :inner="true"
            type="App\Models\Topic"
            />

        </div>
        <comments v-if="card.length != 0"
            comments-type="App\Models\Topic"
            type="App\Models\Topic"
            :item-id="card.id"
            :item-user="card.user ? card.user.id : 0"
        />
    </div>
    <div v-else class="d-flex flex-column">

    </div>
</template>

<script>
    const pagination = () => import("laravel-vue-pagination")
    const BlogCard  = () => import("../components/BlogCard");
    const comments = () => import("../components/Comments/CommentsWrapper");
    export default {
        name: "TopicPage",
        components:{
            pagination,
            BlogCard,
            comments,
        },
        data(){
          return{
              card:[],
              user:null,
              loaded: false,
              title: "",
          }
        },
                computed:{
            pathName(){
                let name =  window.location.pathname.split("/")[2];
                return name[0].toUpperCase()+name.slice(1)
            }

        },
        methods:{
            getInfo(){
                let alias = this.$route.params.contentAlias;
                window.axios.get("/api/topics/"+alias)
                .then(
                    resp => {
                        this.card = resp.data[0]
                        this.title = this.card.title
                        this.user = this.card.user
                    }
                )
            }
        },
        props:[
            "serverData"
        ],
        mounted() {
            if(this.serverData){
                this.card = this.serverData
                this.title=this.card.title
                this.user = this.card.user
            }else{
                this.getInfo();
            }
            this.loaded = true;
        }
    }
</script>


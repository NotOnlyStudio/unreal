<template>
  <div v-if="loaded" class="d-flex flex-column blog-page">

    <div class=" content-right double-column">
            <p class="breadcrumbs">
        <a href="/" style="color: #b3b3b3; text-decoration: none">UnrealShop</a> >
        <a href="/blog" style="color: #b3b3b3; text-decoration: none">Blog</a> >
        {{pathName}}
      </p>
      <div class="container position-relative">
        <author
          :photo="user != null ? user.photo : 'None'"
          :name="user != null ? user.name : 'None'"
          :rank="user != null ? user.rank : 'None'"
          :id="user != null ? user.id : 'None'"
        />
        <blog
          :url="'/blog/' + card.alias"
          :card-title="card.title"
          :author="card.user == null ? 'NONE' : card.user.name"
          :card-id="card.id"
          :author-name="card.user == null ? 'NONE' : card.user.name"
          :rating-plus="card.likes_count"
          :rating-minus="card.dislikes_count"
          :checked="card.user_bookmarks_count == 1 ? true : false"
          :author-id="card.user == null ? 0 : card.user.id"
          :user-assessment="card.user_assessment"
          :content="card.content"
          :show-user="false"
            :inner="true"

        />
      </div>
    </div>
    <div v-if="card.length != 0" class="container d-flex flex-column">
      <comments 
        type="App\Models\Blog" 
        comments-type="App\Models\Blog" 
        :item-id="card.id" 
        :item-user="card.user ? card.user.id : 0"
      />
    </div>
  </div>

  <preloader v-else />
</template>

<script>
import axios from "axios";
const author = () => import("../components/Author");
const comments = () => import("../components/Comments/CommentsWrapper");
const blog = () => import("../components/BlogCard");
const preloader = () => import("../components/preloader");
export default {
  name: "BlogPage",
  data() {
    return {
      // alias: this.$route.params.item,
      card: [],
      loaded: false,
      user: null,
      pathName:"",
    };
  },
  props:["serverData"],
  components: {
    comments,
    author,
    blog,
    preloader,
  },
  // methods: {
  //   getArticle() {
  //     axios.get("/blog-get/" + this.alias).then((resp) => {
  //       if (resp.status == 200) {
  //         this.card = resp.data[0];
  //         this.user = this.card.user;
  //         this.pathName = this.card.title
  //       } else {
  //         alert("Article not found");
  //       }
  //     });
  //   },
  // },
  mounted() {
    if(this.serverData){
      this.card = this.serverData
      this.user = this.card.user
      this.pathName = this.card.title
    }else{
      // this.getArticle();
    }
    this.loaded = true;
    this.$store.dispatch("setChallengeStyles",`top:82px;`)
  },
};
</script>

<template>
  <div class="d-flex flex-column content-right double-colum">
        <div class="container"><p class="breadcrumbs"><a href="/" style="color:#b3b3b3;text-decoration: none">UnrealShop</a> > Blog</p></div>
      <p class="block-title tr-none">Blog</p>
      <div class="d-flex container justify-content-start px-0">
          <b-button href="/upload-article" variant="bordered sm">+ Add Artile</b-button>
      </div>
    <div class="container d-flex flex-wrap justify-content-around">
      <blogcard
      v-for="(card, key) in blogs"
            :url="'/blog/'+card.alias"
            :card-title="card.title"
            :key = "key"
            :author="card.user.name "
            :card-id="card.id"
            :show-user="true"
            :author-name = "card.user == null ? 'NONE' : card.user.name"
            :rating-plus="card.likes_count"
            :rating-minus="card.dislikes_count"
            :checked="card.user_bookmarks_count == 1 ? true : false"
            :author-id="card.user == null ? 0 : card.user.id "
            :user-assessment="card.user_assessment"
            :content = "card.content"
      ></blogcard>
    </div>
    <pagination :limit="4" :data="laravelData" @pagination-change-page="getResults">
        <span slot="prev-nav"><img src="/img/icons/arrow.svg" alt="">Previous</span>
        <span slot="next-nav">NEXT <img src="/img/icons/arrow.svg" alt=""></span>
    </pagination>

  </div>
</template>

<script>
import axios from "axios";
import blogcard from "../components/BlogCard";
import { paginationMethods } from "../Mixins/pagination"
const pagination = () => import('laravel-vue-pagination')
    import Challenges from "../components/Challenges/ChallengesWrapper";

export default {
  name: "Blog",
  data() {
    return {
      blogs: [],
      laravelData:[],
      toShow: this.showCount ? this.showCount : 6,
    };
  },
  mixins:[paginationMethods],
  props: ["showCount","serverData"],
  components: {
    blogcard,
    pagination,
Challenges
  },
  methods: {
     getResults(page = 1){
        var newUrl=this.paginationUrl(window.location.href,"page",page);
        window.history.pushState("", document.title, newUrl);
        axios.get("/api/blog?page="+page+"&count=3")
        .then(
            resp =>{
                if(resp.data.data.length != 0){
                    this.blogs = resp.data.data;
                    this.loaded = true;
                    this.laravelData = resp.data;
                    window.scrollTo(0,500)

                }
            }
        )
    }
  },
  mounted() {
    if(this.serverData){
      this.blogs = this.serverData.data
      this.laravelData = this.serverData
      this.loaded = true
    }else{
      this.getResults();
    }
    this.$store.dispatch("setChallengeStyles",`top:60px`)

  },
};
</script>



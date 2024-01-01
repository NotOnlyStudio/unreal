<template>
  <div 
    @click="goToPage" 
    :class="[ 
      havePosition && winnerPosition == 1 ? 'first order-1' : '',
      havePosition && winnerPosition == 2 ? 'second order-2' : '',
      havePosition && winnerPosition == 3 ? 'third order-3' : '',
      'card card-gallery'
    ]"
  >
    <bookmark :item-id="id" item-type="App\Models\Gallery" :checked="checked" />
    <!-- <img v-if="image" :src="image" class="card-img-top" :alt="alt" /> -->
    <picture v-if="image">
      <source type="image/webp" :srcset="image | webpThumb ">
      <source type="image/png" :srcset="image | thumb">
      <source type="image/jpg" :srcset="image | thumb">
      <source type="image/jpeg" :srcset="image | thumb">
      <b-img-lazy class="card-img-top" :src="image | webpThumb" :alt="alt ? alt : 'None' "/>
    </picture>
    <img v-else src="/assets/1render_small.jpg" class="card-img-top" alt="Lamp" />
    <div class="card-body">
      <h5 class="card-title">{{ cardTitle.length > 60 ? cardTitle.slice(0,55)+"..." : cardTitle  }}</h5>
      <div class="d-flex align-items-center justify-content-between">
        <p class="author mb-0">
          author:
          <a :href="'/user/'+authorId" target="_blank">{{
            author.length > 18 ? author.substring(0, 18) + "..." : author
          }}</a>
        </p>
        <rating
          :item-id="id"
          type="App\Models\Gallery"
          :rating-plus="ratingPlus"
          :rating-minus="ratingMinus"
          :assessment="userAssessment"
          :author-id="authorId"
          :item-content="cardTitle"
        />
      </div>
    </div>
  </div>
</template>

<script>
const bookmark = () => import("./Bookmark");
const rating = () => import("./Rating/RatingWrapper");
export default {
  name: "GalleryCard",
  props: [
    "url",
    "authorId",
    "image",
    "cardTitle",
    "userAssessment",
    "author",
    "checked",
    "ratingPlus",
    "ratingMinus",
    "alt",
    "id",
    "alias",
    "winnerPosition",
  ],
  filters:{
    webpAdd(data){
        let text = data.split(".")
        text.pop();
        return text+"_thumb.webp"
    },
    thumb(data){
        let text = data.split(".")
        let prefix = text.pop();
        let url = text.join(".")
        return url+"_thumb."+prefix
    },
  },
  computed:{
      havePosition(){
          if(this.winnerPosition){
            return true
          }
          else{
            return false;
          }
      }
  },
  components: {
    bookmark,
    rating,
  },
  methods:{
    goToPage(e){
      let target = e.target.classList
      if(target.contains("minus__icon") || target.contains("plus__icon") || e.target.tagName == "svg" || e.target.tagName == "line" || e.target.tagName == "path"){
        e.preventDefault();
        return false;
      }
      if(e.target.tagName == "A"){
        let href = e.target.getAttribute("href")
        window.open(href,"_blank")
      }
      if(!target.contains("minus__icon") && !target.contains("plus__icon") && !target.contains("bookmark") && e.target.tagName != "A" || target != "" && e.target.tagName != "line"){
        document.location.href = `/gallery/${this.alias}`
      }
      else  
        e.preventDefault()
    }
  },
  mounted(){
    console.log(this.styles + "-------")
  }
};
</script>


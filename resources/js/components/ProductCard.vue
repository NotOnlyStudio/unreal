<template>
  <div
      title="Go to model page"
      @click.prevent="goToPage"
      :class="[
      images.length > 1 ? 'card card-white card-hovered' : 'card card-white',
    ]"
  >
    <div v-if="isFree" class="free-banner" title="Up to three free models per day"><span>Free</span></div>
    <div v-if="isVr" class="free-banner" title="VR"><span>VR</span></div>
    <bookmark :item-id="id" item-type="App\Models\Product" :checked="checked" />
    <picture v-for="(item, key) in images" v-if="key < 2" :key="key">
      <source type="image/webp" :srcset="'/storage/app/public/models/user-' + userId + '/' + item | webpThumb" />
      <source type="image/png" :srcset="$imgRoute+'models/user-' + userId + '/' + item | thumb" />
      <source type="image/jpg" :srcset="$imgRoute+'models/user-' + userId + '/' + item | thumb" />
      <source type="image/jpeg" :srcset="$imgRoute+'models/user-' + userId + '/' + item | thumb" />
      <b-img-lazy
          :src="$imgRoute+'models/user-' + userId + '/' + item | webpThumb"
          class="card-img-top"
          :alt="item.alt"
      />
    </picture>

    <div class="card-body">
      <h5 class="card-title">{{ cardTitle.length > 60 ? cardTitle.slice(0,55)+"..." : cardTitle }}</h5>
      <card-button
          :item-id="id"
          :button-download="(isBuyed || isFree) ? true : false"
          :is-free="isFree"
          @show-alert="showAlert"
          :buy-text="(isBuyed || isFree) ? 'Download' : 'Buy Now'"
          type="btn-bordered"
      />
    </div>

  </div>
</template>

<script>
const bookmark = () => import("./Bookmark");
const CartButton = () => import("./Models/CartButton")
export default {
  name: "ProductCard",
  props: ["url", "images", "cardTitle", "userId", "id", "checked","buyed","isFree", "isVr", "buttonDownload"],
  components: {
    bookmark,
    "card-button":CartButton
  },
  data(){
    return{
      buy_text: "Buy Now",
    }
  },
  computed:{
    isBuyed(){
      if(this.buyed == null || this.buyed == -1)
        return false
      else
        return true
    },


  },
  methods:{
    goToPage(e){
      let target = e.target.classList
      console.log(target)
      if(e.target.tagName == "svg" || e.target.tagName == "line" || e.target.tagName == "path"){
        e.preventDefault();
        return false;
      }
      if(e.target.tagName != "A" && target.contains("bookmark") == false)
        window.open(this.url,"_blank")
    },
    showAlert(data){
      this.$bvModal.msgBoxOk(data.text,{
        "title":"Notification"
      })
    }
  },
  mounted(){
    console.log({mountedProductCard:{
        "url":this.url,
        "images":this.images,
        "cardTitle":this.cardTitle,
        "userId":this.userId,
        "id":this.id,
        "checked":this.checked,
        "buyed":this.buyed,
        "isFree":this.isFree,
        "isVr":this.isVr,
        "buttonDownload":this.buttonDownload
      }})
  }
};
</script>


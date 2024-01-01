<template>
   <div class="card card-white card-hovered">
      <Bookmark
         :item-id="cardInfo.bookmark"
         />

        <picture>
            <!-- <source type="image/webp" :srcset="'/storage/app/public/models/user-' + userId + '/' + item | webpThumb" /> -->
            <source type="image/png" :srcset="$imgRoute+'models/user-' + cardInfo.user_id + '/' + images[0] | thumb" />
            <source type="image/jpg" :srcset="$imgRoute+'models/user-' + cardInfo.user_id + '/' + images[0] | thumb" />
            <source type="image/jpeg" :srcset="$imgRoute+'models/user-' + cardInfo.user_id + '/' + images[0] | thumb" />
            <img
                :src="$imgRoute+'models/user-' + cardInfo.user_id + '/' + images[0] | thumb"
                class="card-img-top"
                :alt="cardInfo.title"
            />
        </picture>
     <picture>
            <!-- <source type="image/webp" :srcset="'/storage/app/public/models/user-' + userId + '/' + item | webpThumb" /> -->
            <source type="image/png" :srcset="$imgRoute+'models/user-' + cardInfo.user_id + '/' + images[1] | thumb" />
            <source type="image/jpg" :srcset="$imgRoute+'models/user-' + cardInfo.user_id + '/' + images[1] | thumb" />
            <source type="image/jpeg" :srcset="$imgRoute+'models/user-' + cardInfo.user_id + '/' + images[1] | thumb" />
            <img
                :src="$imgRoute+'models/user-' + cardInfo.user_id + '/' + images[1] | thumb"
                class="card-img-top"
                :alt="cardInfo.title"
            />
        </picture>
      <div class="card-body">
         <h5 class="card-title">{{cardInfo.title}}</h5>
         <a :href="'/downloads/model/id-'+cardInfo.id"  class="btn btn-bordered">Download</a>
      </div>
   </div>
</template>
<script>
   import Bookmark from '../../components/Bookmark.vue'
   export default {
       name:"PurchaseCard",
       props:["cardInfo"],
       components:{
           Bookmark
       },
       computed:{
           images(){
               return JSON.parse(this.cardInfo.photos)
           },
       }
   }
</script>
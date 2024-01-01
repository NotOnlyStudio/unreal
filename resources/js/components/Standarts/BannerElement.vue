<template>
  <div class="w-100 banner container-fluid main">
    <iframe
        v-if="avardPhoto == ''"
        width="560"
        loading="lazy"
        height="315"
        src="https://www.youtube.com/embed/Hka_Q0YM8Lw?autoplay=1&loop=1&enablejsapi=1&&playerapiid=featuredytplayer&controls=0&modestbranding=1&rel=0&showinfo=0&color=white&iv_load_policy=3&theme=light&wmode=transparent&mute=1"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
    ></iframe>
    <img v-else :src="avardPhoto" alt="" class="w-100" style="height: 100vh">
    <p class="title">{{ siteName }}</p>
    <p class="description">{{ siteDescription }}</p>
    <p class="awards" v-if="avardName != '' ">award by {{ avardName }}</p>
  </div>
</template>

<script>
import axios from "axios"
export default {
  name: "Banner",
  props: ["siteName", "siteDescription", "awards"],
  data(){
      return{
          avardName:"",
          avardPhoto:"",
          avardUserId:"",
      }
  },
  computed: {
    avard() {
      let banner_url = location.pathname.split("/")[2];
      if (banner_url == "avards") {
        let avard_id = location.pathname.split("/")[3];
        try{
            return Number.isInteger(parseInt(avard_id)) ? avard_id : null;
        }catch(exc){
            return null
        }
      }
    },
  },
  mounted() {
    if(this.avard != null){
        axios.get("/api/avard-banner/"+this.avard)
        .then(
            resp => {
                this.avardUserId = resp.data.users.id
                this.avardPhoto = "/storage/gallery/user-"+this.avardUserId+"/"+JSON.parse(resp.data.users.gallery.photos)[0]
                this.avardName = resp.data.users.name
            }
        )
    }
  },
};
</script>


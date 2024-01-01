<template>
  <div class="w-100 banner container-fluid main" >
    <!-- <iframe
        v-if="avardInfo == null"
        width="560"
        loading="lazy"
        height="315"
        :src="videoSrc+ '?&autoplay=1&loop=1&enablejsapi=1&&playerapiid=featuredytplayer&controls=0&modestbranding=1&rel=0&showinfo=0&color=white&iv_load_policy=3&theme=light&wmode=transparent&mute=1&loop=1'"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
    ></iframe> -->
      <img src="/assets/cover.jpg"  v-if="wind_width > 600" alt="Video img">
<!--       <youtube-video loaded && avardInfo == null &&-->
<!--          :mute="true"-->
<!--          v-else-if="avardInfo == null && loaded && wind_width > 600"-->
<!--          :autoplay="true"-->
<!--          :iframe-attributes="video.iframeAttributes"-->
<!--          :src="video.url"-->
<!--          :preview-image-size="video.previewImageSize"-->
<!--          :aspect-ratio="video.aspectRatio"-->
<!--        />-->
<!--        <img v-else :src="avardPhoto" alt="" class="w-100" style="height: 100vh">-->

    <p class="title">{{ siteName }}</p>
    <p class="description">{{ siteDescription }}</p>
  </div>
</template>

<!--<script>-->
<!--const LazyYoutubeVideo  = () =>import('vue-lazy-youtube-video')-->
<script>
export default {
  props: ["siteName", "siteDescription", "avard","videoIframe"],
  data(){
    return {
      wind_width:window.innerWidth,
    }
  },
}
</script>

<!--export default {-->
<!--  name: "Banner",-->
<!--  props: ["siteName", "siteDescription", "avard","videoIframe"],-->
<!--  data(){-->
<!--      return{-->
<!--          avardName:"",-->
<!--          avardPhoto:"",-->
<!--          videoSrc: "",-->
<!--          autoplay: false,-->
<!--          loaded: false,-->
<!--          avardUserId:"",-->
<!--          wind_width:window.innerWidth,-->
<!--          video:{-->
<!--            url: this.videoSrc+ '?vq=144&autoplay=1&loop=1&enablejsapi=1&mute=1&playerapiid=featuredytplayer&controls=0&modestbranding=1&rel=0&showinfo=0&color=white&iv_load_policy=3&theme=light&wmode=transparent&loop=1',-->
<!--            previewImageSize: "maxresdefault",-->
<!--            aspectRatio: "1:1",-->
<!--            iframeAttributes:{-->
<!--              autoplay: true,-->
<!--              allow:"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture",-->
<!--              loading:"lazy",-->
<!--              frameborder:"0",-->
<!--              mute: true,-->
<!--            }-->
<!--          },-->
<!--      }-->
<!--  },-->
<!--  components:{-->
<!--    "youtube-video":LazyYoutubeVideo-->
<!--  },-->
<!--  computed: {-->
<!--    avardInfo() {-->
<!--      if(this.avard){-->
<!--        return this.avard-->
<!--      }else{-->
<!--        return null-->
<!--      }-->
<!--    },-->
<!--  },-->

<!--  mounted() {-->
<!--    window.addEventListener('load', () => {-->
<!--      this.loaded = true-->
<!--    })-->
<!--    if(this.avard != null){-->
<!--      this.avardUserId = this.avardInfo.user.id-->
<!--      this.avardPhoto = this.$imgRoute+"gallery/user-"+this.avardInfo.user.id+"/"+JSON.parse(this.avardInfo.user.gallery.photos)[0]-->
<!--      this.avardName = this.avardInfo.user.name-->
<!--    }else{-->
<!--      axios.get("/get-video")-->
<!--      .then(-->
<!--        resp => {-->
<!--          this.videoSrc = resp.data.video-->
<!--          this.video.url = this.videoSrc-->
<!--        }-->
<!--      )-->
<!--    }-->
<!--    this.autoplay = true-->
<!--    console.log(123)-->

<!--  }-->
<!--};-->
<!--</script>-->


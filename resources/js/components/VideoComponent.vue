<template>
  <div class="search__bg" style="" @keydown.esc="closeModal">
    <div class="search__inner"  >
      <div class="video_bg p-lg-4 p-md-3 p-1 d-flex flex-column" v-click-outside="closeModal" style="background: white">
          <p class="text-bold text-orange fsz17" v-text="videoTitle"></p>
          <div v-html="videoIframe"></div>
      </div>
    </div>
  </div>
</template>


<script type="text/javascript">
export default {
  name: "VideoComponent",
  props: ["videoIframe","videoTitle"],
  methods:{
      closeModal(){
          this.$emit("close-video")
      }
  }
};
</script>

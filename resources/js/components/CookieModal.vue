<template>
  <div :class="[ accepted ? 'accepted' : '' ,'cookie-modal']" >
    <b-icon  class="warning" icon="exclamation-circle-fill" variant="warning"></b-icon>
    <div class="cookie-modal__inner d-flex flex-column">
      <p class="title">Privacy notice</p>
      <p class="description">
            We use cookies and similiar technologies to remember your preferences,
            measure effectiveness of our campaigns, and analyze
            depersonalized data to improve
            perfomance of our site. By choosing "Accept", you consent to the use cookies.
      </p>
      <div class="d-flex">
        <button class="btn btn-dark" @click="accept__cookies">Accept</button
        ><a href="" class="decline" @click="decline">Decline</a>
      </div>
    </div>
    <button class="close__cookies"><b-icon-x/></button>
  </div>
</template>

<script>
export default {
  name: "CookieModal",
  data(){
      return{
          accepted: false,
      }
  },
  methods: {
      decline(){
          window.close();
      },
      accept__cookies(){
          $cookies.set("acceptedCookies", true);
          this.accepted = true
      }
  },
};
</script>
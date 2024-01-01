<template>
  <div :class="[userAssessment == true ? 'active' : '' ,'d-flex minus']" @click.prevent="newMinus">
    <span>{{ dlikesCount == undefined ? 0 : dlikesCount }}</span>
    <div class="minus__icon">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="6"
        height="1.5"
        viewBox="0 0 6 1.5"
      >
        <line
          id="Line_2"
          data-name="Line 2"
          x2="6"
          transform="translate(0 0.75)"
          fill="none"
          stroke="#ff8562"
          stroke-width="1.5"
        ></line>
      </svg>
    </div>
  </div>
</template>

<script>
export default {
  name: "Minus",
  props: ["count", "type", "itemId","assessment"],
  data(){
      return{
            dlikesCount: this.count,
        
      }
  },
  computed:{
    userAssessment: function(){
        if(this.assessment != null && this.assessment.like == 0){
            return true
        }else{
            return false;
        }
    }
  },
  methods: {
    newMinus(e) {
      e.preventDefault()
      let user = $cookies.get("user");
      if (user) {
        this.$emit("changeRating",{
          "type":this.type,
          "itemId":this.itemId,
          "user_id":user.id,
          "ratingType":0,
        })
      } else {
        document.location.href = "/login";
      }
    },
  },
  mounted() {
  },
};
</script>


<template>
  <a class="shopBag" @click="clickEvent">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="31"
      height="37.912"
      viewBox="0 0 31 37.912"
    >
      <g
        id="Group_14"
        data-name="Group 14"
        transform="translate(-1758 -1071.088)"
      >
        <g
          id="Rectangle_59"
          data-name="Rectangle 59"
          transform="translate(1758 1078)"
          fill="none"
          stroke="#000"
          stroke-width="2.5"
        >
          <path
            d="M0,0H31a0,0,0,0,1,0,0V21A10,10,0,0,1,21,31H10A10,10,0,0,1,0,21V0A0,0,0,0,1,0,0Z"
            stroke="none"
          />
          <path
            d="M2.5,1.25h26A1.25,1.25,0,0,1,29.75,2.5V21A8.75,8.75,0,0,1,21,29.75H10A8.75,8.75,0,0,1,1.25,21V2.5A1.25,1.25,0,0,1,2.5,1.25Z"
            fill="none"
          />
        </g>
        <path
          id="Path_1"
          data-name="Path 1"
          d="M-22253.758-17689.963c0-8.6,2.65-12.08,6.572-12.158,4.832,0,7.467,3.559,7.467,12.158"
          transform="translate(24020.238 18774.209)"
          fill="none"
          stroke="#000"
          stroke-linecap="round"
          stroke-width="2"
        />
      </g>
    </svg>
    <!-- <span class="count" v-if="basketCount != 0">{{basketCount}}</span> -->
  </a>
</template>

<script>

export default({
    name:"Basket",
    // computed:{
    //   basketCount(){
    //     return this.$store.getters.baksetLength
    //   }
    // },
    methods:{
      clickEvent(){
        document.location.href="/basket"
      }
    }
})
</script>



<template>
  <div
    class="search__bg"
    @keydown.esc="close"
    @click="close"
  >
    <div class="search__inner">
      <form class="search__form position-relative" @click.stop.prevent="retAnti()">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="23.206"
          height="23.206"
          viewBox="0 0 23.206 23.206"
          @click="getResults"
        >
          <g
            id="Group_16"
            data-name="Group 16"
            transform="translate(-41589.794 -6523)"
          >
            <g
              id="Ellipse_2"
              data-name="Ellipse 2"
              transform="translate(41595 6523)"
              fill="none"
              stroke="#707070"
              stroke-width="1"
            >
              <circle cx="9" cy="9" r="9" stroke="none" />
              <circle cx="9" cy="9" r="8.5" fill="none" />
            </g>
            <line
              id="Line_2"
              data-name="Line 2"
              y1="7"
              x2="8"
              transform="translate(41590.5 6538.5)"
              fill="none"
              stroke="#707070"
              stroke-linecap="round"
              stroke-width="1"
            />
          </g>
        </svg>
        <input
          @keydown.esc="close"
          type="text"
          placeholder="Search"
          v-model="search"
          @keydown.enter.prevent.stop="getResults"
        />
        <counts v-if="allCounts != null && search != ''" :counts="allCounts" :text="search" />
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios"
const counts = () => import("./Counts");
export default {
  name: "SearchWrapper",
  props:[
    'counts'
  ],
  components:{
      counts
  },
  data(){
      return{
          allCounts: null,
          search:"",
      }
  },
  methods:{
      close(){
          this.$emit("close",this.allCounts)
      },
      retAnti(){
        return false;
      },
      getResults(){
        axios.get("/api/counts?title="+this.search)
        .then(
            resp => {
              console.log(resp)
               this.allCounts = resp.data[0][0];
            }
        )
      }
  },

};
</script>
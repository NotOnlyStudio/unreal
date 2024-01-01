<template>
  <div>
    <div class="rightMenu">
      <Basket />
      <SearchBtn @getSerch="searchBool = !searchBool" />
    </div>
      <Search :counts="counts" v-if="searchBool" @close="close" />
  </div>
</template>


<script>
import SearchBtn from "./SearchBtn";
import Basket from "./Basket";
const Search = () => import("./SearchWrapper");
export default {
  name: "RightMenu",
  components: {
    SearchBtn,
    Basket,
    Search,
  },
  data() {
    return {
      searchBool: false,
      counts:null,
    };
  },
  methods:{
    close(data){
      this.searchBool = !this.searchBool
      let counts = data
      if(this.counts == null){
        this.counts = counts
      }
    }
  }
};
</script>

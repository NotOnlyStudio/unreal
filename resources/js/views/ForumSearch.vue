<template>
<div>
      <div class="container">
<p class="block-title pt-2">Forum</p>
    <table v-if="laravelData.data.length != 0" class="w-100 table-pr" style="margin-top: 53px">
      <thead>
        <th style="margin-right: 600px" class="w-50 d-block mr-auto">Topics</th>
        <th>Views</th>
        <th>Responses</th>
        <th>Athor</th>
      </thead>
      <tbody class="upl_topics">
      </tbody>
      <TopicsWrapper
        v-for="(topic, key) in laravelData.data"
        :url="'/forum/'+topic.parent.alias"
        :key="key"
        :content="topic"
      />
    </table>
                  <div class="w-100" v-if="laravelData.data.length != 0">
                    <pagination :data="laravelData" @pagination-change-page="getResults">
                        <span slot="prev-nav"><img src="/img/icons/arrow.svg" alt="">Previous</span>
                        <span slot="next-nav">NEXT <img src="/img/icons/arrow.svg" alt=""></span>
                    </pagination>
                </div>
                </div>
    <div class="container-fluid py-3">
          <b-alert v-if="laravelData.data.length == 0" variant="warning" show>Topics not found</b-alert>
    </div>
    <preloader v-if="loaded" />
    </div>
</template>


<script>
import axios from "axios";
const preloader = () => import("../components/preloader");
import pagination from "laravel-vue-pagination";
import TopicsWrapper from "../components/TopicsWrapper";
    import Challenges from "../components/Challenges/ChallengesWrapper";

export default {
  name: "ForumSearch",
  components: {
    preloader,TopicsWrapper,pagination,Challenges
  },
  props:[
    "searchData"
  ],
  data() {
    return {
      laravelData: [],
      loaded: true,
    };
  },
  methods: {
    getResults(page = 1) {
      let title = this.$route.params.title;
      axios.get("/api/get-forum/" + title+"?api=1&page"+page).then((resp) => {
        this.laravelData = resp.searchData;
        this.loaded = false;
      });
    },
  },
  mounted() {
    console.clear()
    console.log(this.searchData)
    if(this.searchData){
        this.laravelData = this.searchData;
        this.loaded = false;
        console.log(this.laravelData)
    }else{
      this.getResults();
    }
  },
};
</script>
<template>
  <div class="content-right double-column">
    <div v-if="loaded" class="container d-flex flex-column">
      <p class="block-title block-title__untransformed">
        3D Challenge ({{ challengeName }})
      </p>
      <div class="d-flex align-items-center challenge-filters justify-content-between">
        <div class="d-flex align-items-center justify-content-between w-100">
          <b-button variant="bordered sm" :href="currentLink != 0 ? '/challenge/'+currentLink : '/upload-art'">+ Add Art</b-button>
          <b-button v-if="currentLink != 0" :href="'/challenge/'+currentLink" variant="red" style="margin-right: 10px">Current challenge</b-button>
            <b-dropdown
              id="dropdown-1"
              text="Challenges"
              variant="red"
              style="z-index: 6"
              v-if="oldChallenges.length != 0"
            >
              <b-dropdown-item class="dropdown-filter__item" >all</b-dropdown-item>
              <b-dropdown-item :class="[alias == item.alias ? 'active' : '' ,'dropdown-filter__item']" v-for="(item,key) in oldChallenges" :key="key" :href="`/challenge/${item.alias}/cards`">{{item.name}}</b-dropdown-item>
            </b-dropdown>
          </div>
      </div>
      <div class="cards challenge-cards">
        <galcard
          v-for="(card, key) in cards"
          :alias="card.alias"
          :card-title="card.title"
          :rating-plus="card.likes_count"
          :rating-minus="card.dislikes_count"
          :user-assessment="card.user_assessment"
          :author="card.user ? card.user.name : 'NONE'"
          :key="key"
          :checked="card.user_bookmarks_count == 1 ? true : false"
          :id="card.id"
          :alt="card.title"
          :winner-position="
            card.winner_position ? card.winner_position : null
          "
          :author-id="card.user == null ? 0 : card.user.id"
          :image="
            '/storage/app/public/gallery/user-' +
            card.user_id +
            '/' +
            JSON.parse(card.photos)[0]
          "
        />
       
      </div>
    </div>

    <div v-else class="d-flex cards w-100 flex-wrap justify-content-between">
      <div class="card card-gallery">
        <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
        <div class="card-body w-100">
          <div class="d-flex justify-content-center">
            <b-skeleton class="card-title w-50"></b-skeleton>
          </div>
          <div class="d-flex justify-content-between">
            <b-skeleton class="w-25" />
            <b-skeleton class="w-25" />
          </div>
        </div>
      </div>
      <div class="card card-gallery">
        <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
        <div class="card-body w-100">
          <div class="d-flex justify-content-center">
            <b-skeleton class="card-title w-50"></b-skeleton>
          </div>
          <div class="d-flex justify-content-between">
            <b-skeleton class="w-25" />
            <b-skeleton class="w-25" />
          </div>
        </div>
      </div>
      <div class="card card-gallery">
        <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
        <div class="card-body w-100">
          <div class="d-flex justify-content-center">
            <b-skeleton class="card-title w-50"></b-skeleton>
          </div>
          <div class="d-flex justify-content-between">
            <b-skeleton class="w-25" />
            <b-skeleton class="w-25" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import galcard from "../components/GalleryCard";
import Challenges from "../components/Challenges/ChallengesWrapper";
const pagination = () => import("laravel-vue-pagination");
export default {
  name: "ChallengeMain",
  components: {
    pagination,
    Challenges,
    galcard,
  },
  props:["alias","currentLink",'challengeName',"serverData"],
  methods: {
  
    getOldChallenges() {
      axios.get("/api/challenges/old").then((resp) => {
        this.oldChallenges = resp.data.challenges;
      });
    },
  },
  data() {
    return {
      loaded: false,
      cards: [],
      laravelData: [],
      oldChallenges: [],
    };
  },
  mounted() {
    
    this.cards = this.serverData.data
    this.getOldChallenges();
   
    this.loaded = true
    if(this.cards.length > 0){
      console.log(this.cards)
    }
   
  },
};
</script>

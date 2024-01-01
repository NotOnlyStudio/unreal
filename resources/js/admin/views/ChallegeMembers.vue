<template>
  <div v-if="loaded">
    <div v-if="works.length != 0">
        <table class="table table-stripped">
            <thead>
                <th>ID</th>
                <th>User</th>
                <th>Link to work</th>
                <th>1st</th>
                <th>2st</th>
                <th>3st</th>
                <th>Remove win</th>
            </thead>
            <tbody>
                <tr :key="key" v-for="(item,key) in works">
                    <td>{{item.id}}</td>
                    <td>{{item.user.name}}</td>
                    <td><b-button :href="'/gallery/'+item.alias" variant="success"><b-icon-link45deg/></b-button></td>
                    <td><b-button @click="userScored(item.id,1,key)" :class="item.challenge_position && item.challenge_position.prize_place == 1 ? 'active__win' : ''" variant="primary">1st</b-button></td>
                    <td><b-button @click="userScored(item.id,2,key)" :class="item.challenge_position && item.challenge_position.prize_place == 2 ? 'active__win' : ''" variant="info">2st</b-button></td>
                    <td><b-button @click="userScored(item.id,3,key)" :class="item.challenge_position && item.challenge_position.prize_place == 3 ? 'active__win' : ''" variant="warning">3st</b-button></td>
                    <td><b-button @click="removeWin(item.id,key)" variant="danger"><b-icon-x/></b-button></td>
                </tr>
            </tbody>
        </table>
         <pagination
             v-if="paginationData != null"
            :data="paginationData"
            @pagination-change-page="getData"
        ></pagination>
    </div>
    <b-alert show v-else> Works not found </b-alert>
  </div>
  <spinner v-else />
</template>


<script>
import spinner from "../components/Spinner";
const pagination = () => import("laravel-vue-pagination");
import axios from "axios";
export default {
  name: "ChallengeMembers",
  data() {
    return {
      paginationData: null,
      works: [],
      loaded: false,
    };
  },
  components: {
    pagination,spinner
  },
  methods: {
    getData(page = 1) {
        let id = this.$route.params.title;
      axios.get("/api/challenge-users/" + id).then((resp) => {
          this.paginationData = resp.data
          this.works = resp.data.data[0].galleries
          console.log(this.works)
      });
    },
    userScored(id, ball,key){
        let challenge_id = this.$route.params.title;
        axios.post("/challenge-winner",{
            "prize_place":ball,
            "challenge":challenge_id,
            "gallery_id":id,
        })
        .then(
            resp => {
              this.loaded = false
              if(this.works[key].challenge_position){
                if(this.works[key].challenge_position.prize_place && this.works[key].challenge_position.prize_place == ball){
                  this.removeWin(id,key)
                  this.works[key].challenge_position.prize_place = null
                }else{
                  this.works[key].challenge_position = {"prize_place":ball}
                }
              }else{
                this.works[key].challenge_position = {"prize_place":ball}
                if(ball == 1){
                  this.works.forEach(
                    (item,key)=>{
                      if(item.key != key && item.challenge_position && item.challenge_position.prize_place == 1){
                        this.works[key].challenge_position = {"prize_place":null}
                      }
                    }
                  )
                  this.works[key].challenge_position = {"prize_place":ball}
                }
              }
              
              this.$nextTick().then(() => {this.loaded = true})
            }
        )

    },
    removeWin(item_id,key){
        this.loaded = false
        let challenge_id = this.$route.params.title;
        axios.post("/challenge-remove-win",{
            "challenge_id":challenge_id,
            "item_id":item_id,
        }).then(
            resp => {
                alert("The position has been withdrawn");
            }
        )
        console.log(this.works[key])
        this.works[key].challenge_position = {prize_place:null}
        this.$nextTick().then(() => {this.loaded = true})
    }
  },
  mounted() {
    this.getData();
    this.loaded = true
    document.querySelector("h1").innerText = "Challenge members"
  },
};
</script>

<style scoped>
  .active__win{
    box-shadow: 0 0 20px rgb(255 215 0 / 90%);
    opacity: .95;
  }
</style>
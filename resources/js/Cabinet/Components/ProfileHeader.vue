<template>
  <div>
    <div class="container mb-5">
      <breadcrumbs />
    </div>
    <div class="d-flex content-right double-column">
      <div class="container">
        <p class="block-title tr-none">Account</p>
        <div class="d-flex flex-column">
          <div class="profile">
            <div class="profile__main">
                <img
                  :src="
                    userInfo.photo == null
                      ? '/img/user.png'
                      : '/storage/app/public/avatars/' + userInfo.photo
                  "
                  alt="avatar"
                />
              <p class="name" :title="userInfo">{{getUserName(userInfo.name)}}</p>
              <p class="rating">
                {{  userInfo.rang.name }} ({{ userInfo.rating }})
              </p>
            </div>
            <div class="profile__info">
              <p>
                <span>Name</span>
                {{userInfo.name}}
              </p>
              <p>
                <span>Specialization:</span>
                {{userInfo.specialization}}
              </p>
              <p>
                <span>Locations:</span>
                {{userInfo.location}}
              </p>
              <p><span>Rating:</span>{{ userInfo   ? userInfo .rating : 0 }}</p>
              <p>
                <span>Web site:</span>
                {{userInfo.website}}
              </p>
              <p v-if="userInfo.signature">
                <span data-v-6bc7b2c1="">Signature:</span>
                {{userInfo.signature}}
              </p>
              <b-button variant="bordered sm" class="mt-3" @click="newDialog">Dialog</b-button>
          </div>
        </div>
      </div>
      <Challenges />
    </div>
    </div>
  </div>
</template>


<script>
import Challenges from "../../components/Challenges/ChallengesWrapper";
import breadcrumbs from "./BreadCrumbs";
export default {
    name:"ProfileHeader",
    props: ["userInfo"],
    components:{
        breadcrumbs,
        Challenges
    },
    methods:{
        getUserName(name){
            if(name.length > 8){
              var sliced = name.slice(0,10);
              return name+"..."
            }else{
              return name
            }
          },
        newDialog(){
            window.axios.post("/create-dialog",{
                "user_id":this.userInfo.id
            })
            .then(
                resp => {
                    document.location.href="/cabinet/messages"
                }
            )
        }
    },
}
</script>

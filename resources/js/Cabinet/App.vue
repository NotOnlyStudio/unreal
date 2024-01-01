<template>
  <div>
    <div class="container mb-5">
      <breadcrumbs/>
    </div>
    <div class="d-flex content-right double-column">
      <div class="container">
        <p class="block-title tr-none">Account</p>
        <div class="d-flex flex-column">
          <div class="profile">
            <div class="profile__main">
              <img
                :src="
                  user.photo == null
                    ? '/img/user.png'
                    : '/storage/avatars/' + user.photo
                "
                alt=""
              />
              <p class="name">{{ user.name }}</p>
              <p class="rating">
                {{ user.rang ? user.rang.name : "" }} ({{ user.rating }})
              </p>
            </div>
            <form ref="userData" class="profile__info">
              <p class="edit" data-type="name">
                <span>Name</span>
                <input
                  class="user_data-input"
                  type="text"
                  name="name"
                  v-model="eFormData.name"
                  :disabled="eformToggle.name == false ? true : false"
                />
                <span @click="toggleData('name')">{{
                  !eformToggle.name ? "Edit" : "Save"
                }}</span>
              </p>
              <p class="edit" data-type="specialization">
                <span>Specialization:</span>
                <input
                  class="user_data-input"
                  type="text"
                  v-model="eFormData.specialization"
                  name="specialization"
                  :disabled="eformToggle.specialization == false ? true : false"
                />
                <span
                  @click="toggleData('specialization')
                  "
                  >{{ !eformToggle.specialization ? "Edit" : "Save" }}</span
                >
              </p>
              <p><span>E-mail:</span>{{ user.email }}</p>
              <p class="edit" data-type="location">
                <span>Locations:</span>
                <input
                  class="user_data-input"
                  type="text"
                  name="location"

                  v-model="eFormData.location"
                  :disabled="eformToggle.location == false ? true : false"
                />
                <span @click="toggleData('location')">{{
                  !eformToggle.location ? "Edit" : "Save"
                }}</span>
              </p>
              <br />
              <p><span>Rating:</span>{{ user.rating }}</p>
              <p class="edit" data-type="website">
                <span>Web site:</span>
                <input
                  class="user_data-input"
                  type="text"
                  name="website"
                  v-model="eFormData.website"
                  :disabled="eformToggle.website == false ? true : false"
                />
                <span @click="toggleData('website')">{{
                  !eformToggle.website ? "Edit" : "Save"
                }}</span>
              </p>
              <p class="edit" data-type="signature">
                <span>Signature:</span>
                <input
                  class="user_data-input"
                  type="text"
                  name="signature"

                  v-model="eFormData.signature"
                  :disabled="eformToggle.signature == false ? true : false"
                />
                <span @click="toggleData('signature')">{{
                  !eformToggle.signature ? "Edit" : "Save"
                }}</span>
              </p>
            </form>
          </div>
        </div>

        <vnav></vnav>
      </div>
      <Challenges />
    </div>

  </div>
</template>

<script>
import vnav from "./Components/Nav";
import Challenges from "../components/Challenges/ChallengesWrapper";
import axios from "axios";
import breadcrumbs from "./Components/BreadCrumbs";
export default {
  name: "App.vue",
  props: ["userData"],
  data() {
    return {
      user: this.userData != false ? this.userData : null,
      formData: {
        name: "",
        specialization: "",
        location: "",
        website: "",
        signature: "",
        avatar: null,
      },
      eformToggle: {
        name: false,
        specialization: false,
        location: false,
        website: false,
        signature: false,
      },
      eFormData:{
        name: "",
        specialization: "",
        location: "" ,
        website: "" ,
        signature: "",
      },
      alert: false,
    };
  },
  components: {
    vnav,
    Challenges,breadcrumbs
  },
  methods: {
    edit() {
      this.formData.name = this.user.name;
      this.formData.specialization = this.user.specialization;
      this.formData.location = this.user.location;
      this.formData.website = this.user.website;
      this.formData.signature = this.user.signature;
      this.$root.$emit("bv::toggle::collapse", "sidebar");
    },
    userGet() {
      console.log(this.$cookies.get("user"));
      axios
        .get("/api/get-user/" + this.$cookies.get("user").id)
        .then((resp) => {
          console.log(resp);
          this.user = resp.data.user;
          this.eFormData.name = this.user.name
          this.eFormData.specialization =  !this.user.specialization ? "None" : this.user.specialization 
          this.eFormData.location =  !this.user.location ? "None" : this.user.location 
          this.eFormData.website =  !this.user.website ? "None" : this.user.website 
          this.eFormData.signature =  !this.user.signature ? "None" : this.user.signature 
        });
    },
    saveData() {
      let form = new FormData(this.$refs.userData);
      let config = { headers: { "Content-Type": "multipart/form-data" } };
      form.append("user_id", this.user.id);
      if (this.eFormData.name != "") {
        axios.post("/api/user/change", form, config).then((resp) => {
          if (resp.status == 200) {
            let rang = this.user.rang
            this.user = resp.data.user;
            this.user.rang = rang
            $cookies.set("user", this.user);
            document.querySelector(".close").click();
          }
        });
      } else {
        this.alert = true;
      }
    },
    toggleDisabled(e) {
      console.log(e.target);
      e.target.toggleAttribute("disabled");
    },
    toggleData(type){
      if(this.eformToggle[type] == true){
        // axios.post
        console.log("editing")
        this.saveData();
      }
      this.eformToggle[type] = !this.eformToggle[type]
    },

  },
  mounted() {
    if (this.userData == false || this.userData == "undefined")
      document.location.href = "/login";
    this.userGet();
  },
};
</script>
<style scoped>

.user_data-input:disabled {
  background: none;
  border: none;
  line-height: 20px;
  font-weight: bold;
  color: #ff8767;
  font-size: 16px;
}
.edit {
  cursor: pointer;
}
.b-sidebar-header {
  padding: 0.5rem 7px !important;
}
.sidebar___title__ {
  font-family: Candara;
}
.edit {
  position: relative;
  width: fit-content;
}
.user_data-input {
  max-width: 70px;
}
.edit > span:last-child {
  color: gray;
  font-family: Candara;
  font-size: 16px;
  opacity: 0;
  transition: 0.3s linear;
}
.edit:hover > span:last-child {
  opacity: 1;
}
</style>


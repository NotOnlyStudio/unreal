<template>
    <div>
        <transition>
            <upload-form @uploadedPhoto="changeUserPhoto" @keydown.esc="uploadAvatar = false"
                         @closeModal="uploadAvatar = false" v-if="uploadAvatar" v-scroll-lock="uploadAvatar"/>
        </transition>
        <div class="container mb-5">
            <breadcrumbs/>
        </div>
        <div :class="[columns && columns != 0 ? 'double-column' : '','d-flex content-right']">
            <div class="container">
                <p class="block-title tr-none">Account</p>
                <div class="d-flex flex-column">
                    <div class="profile">
                        <div class="profile__main">
                            <form action="" class="avatar-photo">
                                <img
                                    :src="
                    user.photo == null ||  user.photo == undefined
                      ? '/img/user.png'
                      : $imgRoute+'avatars/' + user.photo
                  "
                                    alt="avatar"
                                />
                                <a href="#" title="Change avatar" @click.prevent="clickFile">
                                    <b-icon-person-bounding-box/>
                                </a>
                            </form>
                            <p translate="no" class="name" :title="user ? user.name : 'None'">{{
                                    user ? getUserName(user.name) : "None"
                                }}</p>
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
                                <span @click="toggleData('specialization')">{{
                                        !eformToggle.specialization ? "Edit" : "Save"
                                    }}</span>
                            </p>
                            <p><span>E-mail:</span>{{ user ? user.email : "None" }}</p>
                            <p class="edit" data-type="location">
                                <span>Locations:</span>
                                <input
                                    class="user_data-input"
                                    type="text"
                                    name="location"
                                    disabled
                                    v-model="eFormData.location"
                                    v-if="!eformToggle.location"
                                />
                                <select name="location" id="" @change="selectCountry" v-else>
                                    <option value="None">None</option>
                                    <option
                                        v-for="(item,key) in countries"
                                        :key="key"
                                        :value="item.trim()"
                                        v-text="item"
                                    />
                                </select>
                                <span v-if="eFormData.location == 'None'" @click="toggleData('location')">{{
                                        !eformToggle.location ? "Edit" : "Save"
                                    }}</span>
                            </p>
                            <br/>
                            <p><span>Rating:</span>{{ user ? user.rating : 0 }}</p>
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
                            <p class="edit d-flex" data-type="signature">
                                <span>Signature:</span>
                                <input
                                    class="user_data-input "
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
                <b-alert show variant="light">
                    <div class="d-flex models-counts mt-4 align-items-center">
                        <p>{{ user.models_count }} models available</p>
                        <b-button variant="bordered sm mx-4" href="/basket">Buy models</b-button>
                    </div>
                </b-alert>
                <vnav></vnav>
                <slot></slot>
            </div>
        </div>

    </div>
</template>


<script>
import vnav from "./Nav";
import Challenges from "../../components/Challenges/ChallengesWrapper";
import axios from "axios";
import breadcrumbs from "./BreadCrumbs";

const uploadForm = () => import("./UploadPhoto");
export default {
    name: "CabinetHeader",
    components: {
        vnav,
        Challenges,
        "upload-form": uploadForm, breadcrumbs
    },
    props: ["userInfo", "columns"],
    data() {
        return {
            user: this.userInfo != false ? this.userInfo : null,
            uploadAvatar: false,
            countries: ["Australia", " Austria", " Belgium", " Brazil", " Bulgaria", " Canada", " Cyprus", " Czech Republic", " Denmark", " Estonia", " Finland", " France", " Germany", "Greece", "Hong Kong", "Hungary", "India", " Ireland", " Italy", " Japan", " Latvia", " Lithuania", " Luxembourg", " Malta", " Mexico", " Netherlands", " New Zealand", " Norway", " Poland", " Portugal", " Romania", " Singapore", " Slovakia", " Slovenia", " Spain", " Sweden", " Switzerland", "United Kingdom", "United States"],
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
            eFormData: {
                name: "",
                specialization: "",
                location: "",
                website: "",
                signature: "",
            },
            alert: false,
        };
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
        getUserName(name) {
            if (name.length > 8) {
                var sliced = name.slice(0, 10);
                return name + "..."
            } else {
                return name
            }
        },
        userGet() {
            axios
                .get("/api/get-user/" + this.$cookies.get("user").id)
                .then((resp) => {
                    this.user = resp.data.user;
                    this.eFormData.name = this.user.name;
                    this.eFormData.specialization = !this.user.specialization
                        ? "None"
                        : this.user.specialization;
                    this.eFormData.location = !this.user.location
                        ? "None"
                        : this.user.location;
                    this.eFormData.website = !this.user.website
                        ? "None"
                        : this.user.website;
                    this.eFormData.signature = !this.user.signature
                        ? "None"
                        : this.user.signature;
                });
        },
        selectCountry() {
            let alert = confirm("After changing the country, you cannot change the value!")
            if (alert) {
                axios.post("/change-location", {
                    "location": document.querySelector(`select[name="location"]`).value
                })
                    .then(
                        resp => {
                            if (resp.data.answer == "success") {
                                location.reload();
                            } else {
                                console.warn("Not first request")
                            }
                        }
                    )
            }
        },
        saveData() {
            let form = new FormData(this.$refs.userData);
            let config = {headers: {"Content-Type": "multipart/form-data"}};
            form.append("user_id", this.user.id);
            if (this.eFormData.name != "") {
                axios.post("/user/change", form, config).then((resp) => {
                    if (resp.status == 200) {
                        let rang = this.user.rang;
                        this.user = resp.data.user;
                        this.user.rang = rang;
                        $cookies.set("user", this.user);
                        document.querySelector(".close").click();
                    }
                });
            } else {
                this.alert = true;
            }
        },
        toggleDisabled(e) {
            e.target.toggleAttribute("disabled");
        },
        toggleData(type) {
            if (this.eformToggle[type] == true) {
                this.saveData();
            }
            if (this.type == "location" && this.eFormData.location == 'None')
                this.eformToggle[type] = !this.eformToggle[type];
            if (this.type != "location")
                this.eformToggle[type] = !this.eformToggle[type];
        },
        clickFile() {
            this.uploadAvatar = true;
        },
        changeUserPhoto(data) {
            console.log(data)
            this.user.photo = data.photo
        },
        eachWidths() {
            document.querySelectorAll(".profile__info input").forEach(each => {
                each.style.maxWidth = each.value.length * 10 + "px"
                each.style.width = each.value.length * 10 + "px"
            })
        },

    },
    mounted() {
        if (this.userData == false || this.userData == "undefined")
            document.location.href = "/login";
        if (this.userInfo) {
            this.user = this.userInfo[0];
            this.eFormData.name = this.user.name;
            this.eFormData.specialization = !this.user.specialization
                ? "None"
                : this.user.specialization;
            this.eFormData.location = !this.user.location
                ? "None"
                : this.user.location;
            this.eFormData.website = !this.user.website ? "None" : this.user.website;
            this.eFormData.signature = !this.user.signature
                ? "None"
                : this.user.signature;
        } else {
            this.userGet();
        }
        document.querySelectorAll("input").forEach(inp => {
            inp.onchange = (e) => {
                this.eachWidths();
            }
        })
        this.$store.dispatch("setChallengeStyles", `top:65px;`)
    },
    created() {
        setTimeout(() => {
            this.eachWidths();
        }, 1500)
    }
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

.models-counts p {
    font-family: 'GhothamPro';
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


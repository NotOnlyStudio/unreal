<template>
    <div v-if="loaded">
        <div class="accordion" role="tablist">
            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-1 variant="info"><b-icon-camera-video variant="light" class="mr-2"/>Background video</b-button>
                </b-card-header>
                <b-collapse id="accordion-1" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <b-form>
                            <b-form-group
                                label="Background video"
                                label-for="background"
                            >
                                <b-textarea
                                    placeholder="Youtube Iframe"
                                    id="background"
                                    v-model="backgroundVideo"
                                />
                            </b-form-group>
                            <div class="d-flex justify-content-end mt-2">
                                <b-button @click="saveVideo" variant="success"><b-icon-upload class="mr-2" />Save</b-button>
                            </div>
                        </b-form>
                    </b-card-body>
                </b-collapse>
            </b-card>
             <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-2 variant="info"><b-icon-file-person variant="light" class="mr-2"/>Admins</b-button>
                </b-card-header>
                <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <div class="d-flex flex-column" v-if="admins.length != 0">
                                <table class="table table-stripped">
                                    <thead class="thead-dark">
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Permision</th>
                                        <th>Delete</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(admin, key) in admins" :key="key">
                                            <td>{{admin.id}}</td>
                                            <td>{{admin.name}}</td>
                                            <td>ADMIN</td>
                                            <td><b-button variant="danger" @click="deletePermision(admin.id)"><b-icon-x/></b-button></td>
                                        </tr>
                                    </tbody>
                                </table>
                        </div>
                        <div class="d-flex mt-2 justify-content-end">
                            <b-button variant="success" v-b-modal.modal-1 @click="activePermision = 'ADMIN'"><b-icon-upload class="mr-2"/>Add</b-button>
                        </div>
                    </b-card-body>
                </b-collapse>
            </b-card>
             <b-card no-body  class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-3 variant="info"><b-icon-pencil-square variant="light" class="mr-2"/>Moderators</b-button>
                </b-card-header>
                <b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <div class="d-flex flex-column" v-if="moderators.length != 0">
                            <table  class="table table-stripped">
                                <thead class="thead-dark">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Permision</th>
                                    <th>Delete</th>
                                </thead>
                                <tbody>
                                    <tr v-for="(admin, key) in moderators" :key="key">
                                        <td>{{admin.id}}</td>
                                        <td>{{admin.name}}</td>
                                        <td>MODERATOR</td>
                                        <td><b-button variant="danger" @click="deletePermision(admin.id, 'moder')"><b-icon-x/></b-button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex mt-2 justify-content-end">
                            <b-button variant="success" v-b-modal.modal-1 @click="activePermision = 'MODERATOR'"><b-icon-upload class="mr-2"/>Add</b-button>
                        </div>
                    </b-card-body>
                </b-collapse>
            </b-card>
            <b-card no-body class="mb-1">
                  <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-4 variant="info"><b-icon-diagram2 variant="light" class="mr-2"/>Social networks</b-button>
                </b-card-header>
                <b-collapse id="accordion-4" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <b-form>
                            <b-form-group
                                label="YouTube"
                                label-for="youtube"
                            >
                                <b-form-input id="youtube" v-model="socials.youtube" name="youtube"/>
                            </b-form-group>
                            <b-form-group
                                label="Instagram"
                                label-for="instagram"
                            >
                                <b-form-input id="instagram" v-model="socials.instagram" name="instagram"/>
                            </b-form-group>
                            <b-form-group
                                label="Facebook"
                                label-for="facebook"
                            >
                                <b-form-input id="facebook" v-model="socials.facebook" name="facebook"/>
                            </b-form-group>
                            <div class="d-flex mt-3 justify-content-end">
                                <b-button variant="success" @click="saveSocials"><b-icon-download class="mr-2" variant="light"/>Save</b-button>
                            </div>
                        </b-form>
                    </b-card-body>
                </b-collapse>
            </b-card>
             <b-card no-body class="mb-1">
                  <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-5 variant="info"><b-icon-binoculars variant="light" class="mr-2"/>Meta</b-button>
                </b-card-header>
                <b-collapse id="accordion-5" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <b-form>
                            <b-form-group
                                label="Description"
                                label-for="description"
                            >
                                <b-form-input id="description" v-model="metaData.description" name="description"/>
                            </b-form-group>
                            <b-form-group
                                label="Keywords"
                                label-for="keywords"
                            >
                                <b-form-input id="keywords" v-model="metaData.keywords" name="keywords"/>
                            </b-form-group>

                            <div class="d-flex mt-3 justify-content-end">
                                <b-button variant="success" @click="saveMeta"><b-icon-download class="mr-2" variant="light"/>Save</b-button>
                            </div>
                        </b-form>
                    </b-card-body>
                </b-collapse>
            </b-card>
            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-6 variant="info">Site settings</b-button>
                </b-card-header>
                <b-collapse id="accordion-6" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <p>
                            technical service
                            <button
                                class="ui button big"
                                :class="[isActive ? 'bg-success' : 'bg-danger']"
                                @click="toggle"
                            >{{isActive ? 'ON' : 'OFF'}}</button>
                        </p>
                    </b-card-body>
                </b-collapse>
            </b-card>
        </div>
        <b-modal @close="this.searched = false" hide-footer id="modal-1" title="Add admin">
            <b-form class="py-3" v-if="!searched">
                <b-form-group
                    label="Input user name"
                    label-for="admin"
                >
                    <b-form-input v-model="name" id="admin"/>
                </b-form-group>
                <div class="d-flex mt-2 justify-content-end">
                    <b-button variant="success" @click.prevent="searchUsers"><b-icon-search class="mr-2"/>Search</b-button>
                </div>
            </b-form>
            <div class="d-flex flex-column" v-else>
                <a v-for="(user, key) in users" :key="key" :href="user.id" @click.prevent="setPermision">{{user.name}}</a>
                <div class="d-flex mt-2 justify-content-end">
                    <a @click.prevent="searched = false" href="#">Cancel</a>
                </div>
            </div>
        </b-modal>
    </div>
    <spinner
        v-else
    />
</template>

<script>
import spinner from "../components/Spinner"
import axios from "axios"
export default {
    name:"Settings",
    components:{
        spinner
    },
    data(){
        return{
            loaded: false,
            backgroundVideo: "",
            admins:[],
            name:"",
            searched: false,
            users:[],
            permited:[],
            isActive: false,
            admins:[],
            moderators:[],
            activePermision: "MODERATOR",
            socials:{
                youtube:"",
                facebook:"",
                instagram:"",
            },
            metaData:{
                description:"",
                keywords:"",
            }
        }
    },
    methods:{
        saveSocials(){
            axios.post("/save-links", {
                "links":JSON.stringify(this.socials)
            })
            .then(
                resp => {
                    this.makeToast("Social networks was saved")
                }
            )
        },
        getSocials(){
            axios.get("/social-links")
            .then(
                resp => {
                    this.socials = JSON.parse(resp.data.links)
                }
            )
        },
        getStatus(){
            axios.get("/site-status")
            .then(
                resp => {
                    this.isActive = resp.data
                }
            )
        },
        saveMeta(){
            console.log(this.metaData)
            axios.post("/meta/set",{
                meta:JSON.stringify(this.metaData)
            })
            .then(
                resp => {
                    console.log(resp)
                    this.makeToast("Meta data was saved")
                }
            )
        },
        getMeta(){
            axios.get("/get-main-meta")
            .then(
                resp => {
                    this.metaData = JSON.parse(resp.data.meta);
                }
            )
            .catch(function (error) {
              console.log(error.toJSON());
            });
        },
        toggle() {
            if (!this.isActive) {
                axios.get("/change-status?active=1")
                    .then(
                        resp => {
                            this.isActive = true;
                        }
                    )
            } else {
                axios.get("/change-status?active=0")
                    .then(
                        resp => {
                            this.isActive = false;
                        }
                    )
            }
        },
        setPermision(e){
            let id = e.target.getAttribute("href")
            axios.post(`/user-${id}/add-permission`,{
                "permission":this.activePermision
            })
            .then(
                resp => {
                    this.checkAndRemove(resp.data.user.id,this.activePermision)
                    if(this.activePermision == "ADMIN"){
                        this.admins.push(resp.data.user)
                    }else{
                        this.moderators.push(resp.data.user)
                    }
                    this.makeToast(`User now is ${this.activePermision}`)

                }
            )
        },
        searchUsers(){
            axios.get("/find-user?name="+this.name)
            .then(
                resp => {
                    this.users = resp.data.users
                    this.searched = true
                }
            )
        },
        deletePermision(key, role='admin'){
            if (role == 'moder')
            {
              axios.post("/to-user/"+key)
                  .then(
                      resp => {
                        if(resp.status == 200){
                          let nums = this.moderators.findIndex(el => el.id == key);
                          this.moderators.splice(nums, 1)
                          this.makeToast("Permission was deleted")
                        }
                      }
                  )
            } else {
              axios.post("/to-user/"+key)
                  .then(
                      resp => {
                        if(resp.status == 200){
                          let nums = this.admins.findIndex(el => el.id == key);
                          this.admins.splice(nums, 1)
                          this.makeToast("Permission was deleted")
                        }
                      }
                  )
            }
        },
        setPermission(id){
            axios.post(`/user-${id}/add-permission`,{
                "permission":this.activePermision
            }).then(
                resp => {

                    this.checkAndRemove(resp.data.user.id,this.activePermision)
                    if(this.activePermision == "ADMIN"){
                        if(this.admins.filter(item => item.permisions == "ADMIN").length == 0 )
                            this.admins.push(resp.data.user)
                        else
                            this.makeToast("User was be admin")
                    }else{
                        if(this.admins.filter(item => item.permisions == "MODERATOR").length == 0 )
                            this.moderators.push(resp.data.user)
                         else
                            this.makeToast("User was be moderator")
                    }

                }
            )
        },
        checkAndRemove(id,role){
            console.log(role)
            if(role == "MODERATOR")
            {
                let index = this.admins.findIndex(item => item.id == id)
                console.log("Index " + index)
                if(index != -1)
                    this.admins.splice(index,1)
            }else{
                let index = this.moderators.findIndex(item => item.id == id)
                console.log("Index " + index)
                if(index != -1)
                    this.moderators.splice(index,1)
            }
        },
        getPermitedUsers(){
            axios.get("/permited-users")
            .then(
                resp => {
                    this.permited = resp.data.users
                    this.admins = this.permited.filter(item => item.permisions == "ADMIN")
                    this.moderators = this.permited.filter(item => item.permisions == "MODERATOR")
                }
            )
        },
        saveVideo(){
            let playlist = this.backgroundVideo.split("/");
            axios.post("/save-video",{
                "background":this.backgroundVideo + `?playlist=${playlist[playlist.length-1]}&vq=hd720&loop=1&mute=1rel=0&autoplay=1&controls=0&showinfo=0`
            })
            .then(
                resp => {
                    console.warn(resp)
                    if(resp.status == 200){
                        this.makeToast("Video was saved")
                    }else{

                        alert("ERROR")
                    }
                }
            )
        },
        makeToast(message,append = false) {
                this.$bvToast.toast(message, {
                title: 'UnrealShop notification',
                autoHideDelay: 5000,
                appendToast: append,
                variant: "success"
            })
        },
        openModal(modalRef){
            this.$refs[modalRef].show()
        }
    },
    mounted(){
        if(this.$role != "ADMIN"){
            document.location.href="/admin"
        }
        axios.get("/get-video")
        .then(
            resp => {
                this.backgroundVideo = resp.data.video
            }
        )
        this.getSocials();
        this.getPermitedUsers();
        this.getMeta();
        this.getStatus();
        this.loaded = true
    }
}
</script>

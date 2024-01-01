<template>
    <div v-if="loaded">
        <b-form method="get" class="my-3 d-flex">
            <div class="d-flex justify-content-start">
                <b-form-input name="name" :value="this.$route.query.name ? this.$route.query.name : '' " style="width: auto" type="text" placeholder="Enter name"/>
                <b-button type="submit" class="mx-2">Search</b-button>
            </div>
            <b-button variant="info" href="?banned=1">Only banned</b-button>
            <b-button variant="danger" href="?delete" style="margin-left: 5px;">Only user delete</b-button>
        </b-form>
        <table class="table table-striped">
            <thead class="thead-dark">
                <th>ID</th>
                <th>Name</th>
                <th>E-mail</th>
                <th>Avatar</th>
                <th>Block</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Used</th>
                <th>Available</th>
                <th>Add</th>
                <th></th>
            </thead>
            <tbody>

                <tr v-for="(user,key) in users">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>
                        <b-button v-if="user.photo != null" @click="getPhoto(key)" varian="light"><b-icon-card-image/></b-button>
                        <p v-else>NONE</p>
                    </td>
                    <td><b-button :title="user.ban == true ? 'User is banned' : 'User not banned'" :variant="user.ban == true ? 'dark' : 'info'" @click="setBan(key)"><b-icon-person-x-fill/></b-button></td>
                    <td><b-button :href="'/admin/user/'+user.id" target="_blank" variant="primary"><b-icon-vector-pen/></b-button></td>
                    <td><b-button v-if="user.deleted_at" @click="forceDelete(key)" variant="danger">foreve</b-button>
                        <b-button v-if="user.deleted_at" @click="restoreUser(key)" variant="success">restore</b-button>
                    <b-button v-if="!user.deleted_at" @click="deleteUser(key)" variant="danger"><b-icon-x/></b-button>

                    </td>
                    <td><b-form-input name="usedMoney" :value="user.models_used" style="width: 60px" type="text" disabled/></td>
                    <td><b-form-input name="AvailMoney" :value="user.models_count" style="width: 60px" type="text" disabled/></td>
                    <td><b-form-input name="addMoney" :value="0" style="width: 60px" type="number" v-model="myInput"/></td>
                    <td><b-button @click="forceAddModel(key, myInput)" variant="success">Add</b-button></td>

                </tr>
            </tbody>
        </table>
        <div v-show="showPhoto"  @keydown.esc="hidePhotoEsc" @click="hidePhoto" class="img__wrapper">
            <div class="img__inner">
                <button class="close">
                    <span>&times;</span>
                </button>
                <img :src="photoSrc" alt="Big photo"></div>
        </div>
        <pagination  :limit="4" :data="laravelData" @pagination-change-page="getResults">
            <span slot="prev-nav">&lt; Previous</span>
            <span slot="next-nav">Next &gt;</span>
        </pagination>
    </div>
    <spinner v-else></spinner>
</template>

<script>
import axios from "axios"
import spinner from "../components/Spinner";
import pagination from 'laravel-vue-pagination';

export default {
        name: "User",
        data(){
            return{
                users:"",
                loaded: false,
                laravelData: null,
                photoSrc: "#",
                showPhoto: false,
            }
        },
        methods:{

            getResults(page = 1) {
                let params = this.$route.query.name;
                params = params ? '&name='+params : '';
                // console.log(params)
                let url = "";
                if(window.location.search != ""){
                    url = "/users/get"+window.location.search+"&page="+page+params ;
                }
                else{
                    url = "/users/get?page="+page+params
                }
                axios.get(url)
                    .then(response => {
                        this.users = response.data.data;
                        console.log(response)
                        this.laravelData = response.data;
                        this.loaded = true;
                    });
            },
            deleteUser(key){
                let id = this.users[key].id;
                axios.delete("/user/"+id)
                .then(
                    resp => {
                        if(resp.data.success == true){
                            this.users.splice(key,1);
                        }
                    }
                )
            },
            forceDelete(key){
                let id = this.users[key].id;
                axios.delete("/user/force-delete/"+id)
                .then(
                    resp => {
                        if(resp.data.success == true){
                            this.users.splice(key,1);
                        }
                    }
                )
            },
            forceAddModel(key, models){
                let id = this.users[key].id;
                axios.post("/user/add-model-user/"+id+"/"+models)
                    .then(
                        resp => {
                            if(resp.data.success == true){
                                this.users.splice(key,1);
                            }
                        }
                    );
                window.location.reload();
            },
            restoreUser(key){
                let id = this.users[key].id;
                axios.post("/user/restore/"+id)
                .then(
                    resp => {
                        if(resp.data.success == true){
                            this.users.splice(key,1);
                        }
                    }
                )
            },
            hidePhoto(e){
                if(e.target.tagName != "IMG"){
                    this.showPhoto = false;
                }
            },
            getPhoto(key){
                let src = this.users[key].photo
                this.photoSrc = "/storage/app/public/avatars/"+src.replace(/"/g, '');
                this.showPhoto = true;
            },
            hidePhotoEsc(){
                this.showPhoto = false;
            },
            reload(){
                this.loaded = false;
                this.$nextTick(() => {
                    this.loaded = true
                })
            },
            setBan(key,e){
                let id = this.users[key].id;
                this.users[key].ban  = !this.users[key].ban

                 axios.post("/users/ban/"+id)
                    .then(
                        r => {
                            this.makeToast(`User is ${this.users[key].ban ? 'banned' : 'unbanned'}`)
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
      }
        },
        components: {
            spinner,
            pagination
        },
        mounted(){
            this.getResults()
        }
    }
</script>

<style scoped>
    .close span{
        font-size: 24px;
        color: white;
    }
    .close{
        background: none;
        position: absolute;
        top: 20px;
        user-select: none;
        right: 20px;
        z-index: 15;
        border: none;
        transition: .3s linear;
    }
    img{
        max-width: 90%;
        max-height: 90%;
    }
    .close:hover{
        opacity: .5;
    }
    .img__wrapper{
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        position: fixed;
        z-index: 15;
    }
    .img__inner{
        position: relative;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

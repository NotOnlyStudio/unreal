<template>
    <b-form @submit.stop.prevent="submitForm" ref="loginForm" id="register_form">
        <b-alert variant="primary" v-if="confirmed == 0" show>Confirm registration by mail.</b-alert>
        <b-alert
            :show="alertShow"
            dismissible
            variant="danger"
            @dismissed="alertShow=false"
        >
            <p class="mb-0" v-text="errorMsg"></p>
        </b-alert>
        <b-form-group
            id="input-group-1"
            label="E-mail"
            label-for="email"
        >
        <input type="hidden" name="csrf-token" :value="csrf">

            <b-form-input
                id="email"
                v-model="email"
                type="email"
                name="email"
                required
            ></b-form-input>
        </b-form-group>
        <b-form-group
            id="input-group-2"
            label="Password"
            label-for="password"
        >
            <b-form-input
                id="password"
                v-model="password"
                type="password"
                required
                name="password"
            ></b-form-input>
        </b-form-group>
        <div class="d-flex my-3 sign-buttons justify-content-lg-between  justify-content-md-between  justify-content-center">
            <b-button :href="email == '' ? '/password-reset' : `/password-reset?email=${email}`" variant="bordered" >Forgot password ?</b-button>
            <b-button href="/register" variant="bordered" >+ Join</b-button>
        </div>
        <div class="d-flex mt-4">
            <b-button @click.stop.prevent="submitForm" type="submit" variant="login">Login</b-button>
            <div class="d-flex encrypted">
                <svg xmlns="http://www.w3.org/2000/svg" width="18.863" height="25.151" viewBox="0 0 18.863 25.151">
                    <g id="padlock" transform="translate(-77.375)">
                        <path id="Path_1" data-name="Path 1" d="M93.88,247.844H79.733a2.361,2.361,0,0,1-2.358-2.358v-11a2.361,2.361,0,0,1,2.358-2.358H93.88a2.361,2.361,0,0,1,2.358,2.358v11A2.361,2.361,0,0,1,93.88,247.844ZM79.733,233.7a.787.787,0,0,0-.786.786v11a.787.787,0,0,0,.786.786H93.88a.787.787,0,0,0,.786-.786v-11a.787.787,0,0,0-.786-.786Z" transform="translate(0 -222.693)" fill="#d3d3d3"/>
                        <path id="Path_2" data-name="Path 2" d="M166.54,11a.786.786,0,0,1-.786-.786V6.288a4.716,4.716,0,1,0-9.432,0v3.93a.786.786,0,1,1-1.572,0V6.288a6.288,6.288,0,1,1,12.576,0v3.93A.786.786,0,0,1,166.54,11Z" transform="translate(-74.231)" fill="#d3d3d3"/>
                        <path id="Path_3" data-name="Path 3" d="M260.013,339.483a2.1,2.1,0,1,1,2.1-2.1A2.1,2.1,0,0,1,260.013,339.483Zm0-2.62a.524.524,0,1,0,.524.524A.525.525,0,0,0,260.013,336.864Z" transform="translate(-173.206 -321.668)" fill="#d3d3d3"/>
                        <path id="Path_4" data-name="Path 4" d="M290.942,410.673a.786.786,0,0,1-.786-.786V407a.786.786,0,1,1,1.572,0v2.882A.786.786,0,0,1,290.942,410.673Z" transform="translate(-204.136 -389.713)" fill="#d3d3d3"/>
                    </g>
                </svg>
                <p>All data is transmitted encrypted via a secure<br>TLS connection</p>
            </div>
        </div>
    </b-form>
</template>

<script>
import {mapState, mapActions} from "vuex";

    export default {
        name: "LoginForm",
        props:["confirmed"],
        data(){
            return{
                email:"",
                password:"",
                mails: false,
                terms: false,
                alertShow: false,
                errorMsg: "",
            }
        },
        computed:{
            csrf(){
                return document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        },
        methods:{
            ...mapActions(['login']),
            submitForm(e){
                e.preventDefault()
                    if(this.email != "" && this.password != "" ){
                        let formData = new FormData(this.$refs['loginForm']);

                        axios.defaults.headers = {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        }
                        axios.defaults.withCredentials = true;

                        formData.append("mails",this.mails == "mails"  ?  true : false)
                        axios.post("/login-standart", formData
                        ).then(resp => {
                            if(resp.status == 200){
                                if(window.location.pathname == "/basket")
                                    location.reload()
                                else
                                    showAlert("Invalid login details")
                            }
                            else{
                                this.alertShow = true;
                                this.errorMsg = "Check the entered data";
                            }
                        }).catch(
                            (err) => {
                                if(err.response && err.response.status == 422){
                                    this.errorMsg = "Check the correctness of the entered data"
                                    this.alertShow = true
                                }else{
                                    location.reload()
                                }
                            }
                        )
                    }
            },
            showAlert(text){
                this.errorMsg = text
                this.alertShow = true
            }
        },
        mounted() {
            this.$store.dispatch("logout")
            this.$store.dispatch("setChallengeStyles",`display: none`)
        }

    }
</script>

<style scoped>
    .form-group{
        width: fit-content;
    }
    form{
        width: fit-content;
    }

</style>

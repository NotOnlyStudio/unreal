<template>
    <div>
          <b-alert
            :show="alert"
            dismissible
            :variant="alertType"
            @dismissed="alert=false"
        >
            <p class="mb-0" v-text="errorMsg"></p>
        </b-alert>
         <b-form-group
            id="input-group-1"
            label="E-mail"
            label-for="email"
        >

            <b-form-input
                id="email"
                v-model="email"
                type="email"
                name="email"
                required
            ></b-form-input>
        </b-form-group>
         <div class="d-flex sign-buttons justify-content-end my-3">
            <b-button href="/login" variant="bordered mx-2" >Login</b-button>
            <b-button href="/register" variant="bordered" >+ Join</b-button>
            <b-button href="/passoword-reset" @click.prevent="sendReset" variant="red d-flex align-items-center" style="font-family:'GhothamPro'">Reset Password</b-button>
        </div>
    </div>
</template>

<script>
import {alertConfig} from "../Mixins/alerts"
export default {
    name:"ResetPasswordEmail",
    mixins:[alertConfig],
    data(){
        return{
            email:"",
        }
    },
    computed:{
        userEmail(){
            let search = window.location.search
            if(search != ""){
                search = search.split("&").filter(
                    item => item.indexOf("email=") != -1 ? item : ''
                )
                search = search[0].replace("%40","@").replace("&","").replace("?","").replace("email=","")
                return search;
            }
            else{
                return null;
            }
        },
    },
    methods:{
         sendReset(){
            if(this.email != ""){
                window.axios.post("/reset-password-email",{
                    "email":this.email
                })
                .then(
                    resp => {
                        console.clear()
                        console.log(resp)
                        if(resp.status == 200){
                            this.showAlert("Recovery email was sent. Please, check the mail.","primary")
                        }else{
                            this.showAlert("Check the correctness of the entered data.")
                        }
                    }
                ).catch(
                    err => {
                        console.warn(err.response)
                        if(err.response.status == 422){
                            this.showAlert("User not found.")
                        }else if(err.response == 208){
                            this.showAlert("The application was sent earlier");
                        }else{
                            this.showAlert("Check the correctness of the entered data.")
                        }
                    }
                )
            }else{
                this.showAlert("All required fields are missing.")
            }
        }
    },
    mounted(){
        this.email = this.userEmail
    }

}
</script>

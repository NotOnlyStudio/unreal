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
            label="Password"
            label-for="password"
        >
            <b-form-input
                id="password"
                v-model="password"
                type="password"
                name="password"
                required
            ></b-form-input>
        </b-form-group>
        <b-form-group
                id="input-group-2"
                label="Confirm password"
                class="my-2"
                label-for="confirm"
            >
            <b-form-input
                id="password-confirm"
                type="password"
                v-model="passwordConfirm"
                name="password-confirm"
                required
            ></b-form-input>
        </b-form-group>
        <div class="d-flex sign-buttons justify-content-end my-3">
            <b-button :href="`/password/reset/${this.token}`" @click.prevent="resetPassword" variant="bordered mx-2" >Reset password</b-button>
        </div>
    </div>
</template>

<script>
import {alertConfig} from "../Mixins/alerts"
export default {
    name:"PasswordReset",
    data(){
        return{
            password:"",
            passwordConfirm:"",
        }
    },
    mixins:[alertConfig],
    props:["token","email"],
    methods:{
        resetPassword(){
            if(this.password != "" && this.passwordConfirm != ""){
                if(this.password == this.passwordConfirm){
                    window.axios.post("/reset-passowrd",{
                        "token":this.token,
                        "email":this.email,
                        "password":this.password
                    })
                    .then(
                        resp => {
                            if(resp.status == 200)
                                location.href="/cabinet"
                        }
                    )
                }else{
                    this.showAlert("Password mismatch.")
                }
            }else{
                this.showAlert("Password mismatch.")
            }
        }
    }
}
</script>
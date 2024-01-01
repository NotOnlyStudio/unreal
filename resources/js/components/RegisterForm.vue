<template>
    <b-form v-if="auth" @submit.prevent="onSubmit" id="register_form">
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
            label="Userame"
            label-for="username"
        >
            <b-form-input
                id="username"
                v-model="username"
                type="text"
                required
            ></b-form-input>
        </b-form-group>
        <b-form-group
            id="input-group-1"
            label="E-mail"
            label-for="email"
        >
            <b-form-input
                id="email"
                v-model="email"
                type="email"
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
            ></b-form-input>
        </b-form-group>
        <b-form-group
            id="input-group-2"
            label="Confirm password"
            label-for="confirm"
        >
            <b-form-input
                id="confirm"
                v-model="confirm"
                type="password"
                required
            ></b-form-input>
        </b-form-group>
        <div class="d-flex flex-column mt-3" id="checkboxes">
            <div class="form-group checkboxes"><b-form-checkbox v-model="terms" value="terms" required>I agree with Terms & Conditions</b-form-checkbox></div>
            <div class="form-group checkboxes"><b-form-checkbox v-model="mails" value="mails">Keep me up to date on news and exclusive offers</b-form-checkbox></div>
        </div>
        <input type="hidden" name="_token" :value="csrf">
        <b-button  type="submit" variant="login mt-4">Register</b-button>
        <spinner v-if="preloader" />
    </b-form>
</template>

<script>

    // import Auth from "../helpers/auth"
    const spinner = () => import('./Spinner');
    export default {
        name: "RegisterForm",
        data(){
            return{
                email:"",
                password:"",
                preloader: false,
                confirm:"",
                username:"",
                terms: false,
                mails: false,
                errorMsg: "",
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                alertShow: false,
            }
        },
        components: {
            spinner
        },
        computed:{
            auth(){
                return this.$store.state;
            }
        },
        methods:{
            toast(msg, title, variant = null) {
                this.$bvToast.toast(`${msg}`, {
                    title: `${title}`,
                    solid: true,
                    variant:variant,
                })
            },
            onSubmit(){

                if(this.terms == "terms"){
                    if(this.password.length >= 8) {
                        if (this.password == this.confirm) {
                            this.preloader = true;
                            document.querySelector("body").classList.add("no-overflow")
                            window.axios.post("/register", {
                                name: this.username,
                                password: this.password,
                                mails: this.mails == "mails" ? true : false,
                                email: this.email,
                            }).then((resp) => {
                                if(resp.data == 500){
                                    this.errorMsg = "The data is being used by another user";
                                    this.alertShow = true;
                                }
                                if(resp.data.success == true){
                                    document.location.href="/login"
                                }
                                document.querySelector("body").classList.remove("no-overflow")
                                this.preloader = false;
                            })
                        } else {
                            this.errorMsg = "Password mismatch\n";
                            this.alertShow = true;
                        }
                    }
                    else{
                        this.errorMsg = "Password cannot be less than 8 characters.\n";
                        this.alertShow = true;
                    }
                }else{
                    this.errorMsg = "You must read and agree to the Terms of Service to continue\n";
                    this.alertShow = true;
                }
            },
            loginWithGoogle(){

            }
        },
        created() {
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

<template>
    <div class="container">
        <loginform  v-if="!logined"></loginform>
    </div>
</template>

<script>
    import loginform from "../components/LoginForm";

    export default {
        name: "Login",
        components:{
            loginform
        },
        data(){
            return {
                logined: false,
            }
        },
        computed:{
            auth(){
                return this.$store.state;
            }
        },
        created(){
            console.log(12312)
           if(this.$store.getters.getAuth){
            //   document.location.href = "/"
           }else{
               this.logined = false;
           }

        }
    }
</script>


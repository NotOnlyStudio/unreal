<template>
    <div class="container-sm">
        <registerform v-if="!logined"></registerform>
    </div>
</template>

<script>


    import registerform from '../components/RegisterForm';

    export default {
        name: "Register",
        data(){
            return{
                loged: false,
            }
        },
        data(){
            return {
                logined: true,
            }
        },
        components:{
            registerform,
        },
        created(){
           if(this.$store.getters.getAuth){
              document.location.href = "/"
           }else{
               this.logined = false;
           }

        }
    }
</script>

<style scoped>
    .alert p{
        margin-bottom: 0;
    }
</style>


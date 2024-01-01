<template>
    <div>
        <banner site-name="UnrealShop"  site-description="models for Unreal Engine" :avard="avardPage"/>
        <router-view></router-view>
    </div>
</template>

<script defer>

import router from "./router.js";
import banner from "./components/Banner";

    export default {
        name: "App",
        components:{
            banner
        },
        props:[
            "avardPage"
        ],
        router,
        mounted(){
            console.log(this.avardPage)

        }
    }
</script>

<style scoped>

</style>

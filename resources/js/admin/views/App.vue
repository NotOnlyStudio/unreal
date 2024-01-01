<template>
    <div class="d-flex">
        <side></side>
        <div class="d-flex flex-column">
            <htitle :header-title="headerTitle"></htitle>
            <div class="pl-4">
                <router-view></router-view>

            </div>
        </div>
    </div>

</template>

<script>
    import side from "../components/v-sidebar"
    import htitle from "../components/HeaderTitle";
    export default {
        name: "App",
        data(){
            return{
                headerTitle: document.title,
            }
        },
        components:{
            side,
            htitle
        }
    }
</script>


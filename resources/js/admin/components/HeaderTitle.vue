<template>
    <div class="d-flex w-100">
        <h1 class="mr-auto header-title">
            {{headerTitle}}
        </h1>
    </div>
</template>

<script>
    export default {
        name: "HeaderTitle",
        computed:{
            headerTitle(){
                return this.$route.name;
            }
        }
    }
</script>

<style>
    .header-title{
        padding: 20px 30px;
        font-family: "Arial Black";
        font-size: 30px;
    }
</style>

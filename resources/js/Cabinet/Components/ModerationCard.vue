<template>
    <div title="Card not approved" class="card card-white moderation-card">
        <img :src="$imgRoute+type+'/user-' + userId + '/'+image" alt="..." class="card-img-top">
        <div class="card-body">
            <h5 class="card-title">{{cardTitle}}</h5>
            <a href="#" @click="deleteCard(id)" class="btn btn-bordered btn-remove">Remove</a>
        </div>
    </div>
</template>
<script>
    import axios from "axios"

    export default {
        name: "ModerationCard",
        props:['id','image','cardTitle','keygen','userId',"type"],
        methods:{

            deleteCard(id){
                this.$emit("onDelete",{
                    "key": this.keygen
                })
            }
        },
    }
</script>

<style scoped>
    .card{
        opacity: .5;
    }
</style>

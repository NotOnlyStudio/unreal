<template>
    <div class="author">
        <img :src="userPhoto" alt="">
        <a :href="'/user/'+id" class="name">{{name}}</a>
        <span style="font-size: 9px">{{rank == null ? "rookie" : rank}}</span>
    </div>
</template>

<script>
    export default {
        name: "Author",
        props:[
            'photo',
            'name',
            'rank',
            'id'
        ],
        computed:{
            userPhoto(){
                if(this.photo == undefined ||  this.photo == null){
                    return '/img/user.png';
                }else{
                    return this.$imgRoute+'avatars/'+this.photo;
                }
            } 
        },

    }
</script>


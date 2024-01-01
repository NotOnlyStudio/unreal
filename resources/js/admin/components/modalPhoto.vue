<template>
    <div v-show="photoShow" class="img__wrapper">
        <div class="img__inner">
            <button class="close">
                <span>&times;</span>
            </button>
            <img :src="photo" alt="Big photo"></div>
    </div>
</template>

<script>
    export default {
        name: "modalPhoto",
        props:[
            'photo',
            'photoShow'
        ]
    }
</script>

<style scoped>
    .close span{
        font-size: 24px;
        color: white;
    }
    .close{
        background: none;
        position: absolute;
        top: 20px;
        right: 20px;
        border: none;
        transition: .3s linear;
    }
    img{
        max-width: 90%;
        max-height: 90%;
    }
    .close:hover{
        opacity: .5;
    }
    .img__wrapper{
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        position: fixed;
        z-index: 15;
    }
    .img__inner{
        position: relative;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

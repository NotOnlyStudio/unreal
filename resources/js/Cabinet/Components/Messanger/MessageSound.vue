<template>
    
</template>

<script>
export default {
    name:"MessageSound",
    mounted(){
        var audio = new Audio();
        audio.preload = 'auto';
        audio.src = '/assets/message.mp3';
        audio.play();
    }
}
</script>
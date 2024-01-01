<template>
    <div :class="[ classList != null ? classList : '', 'challenges']" :style="styles == null ? '' : styles">
        <p class="block-title" translate="no"  v-text="$ml.get('Challenges')">Challenges</p>
        <a :href="'/challenge/'+alias"  translate="no" v-text="$ml.get('Join')" class="btn btn-bordered">+ Join</a>
        <picture >
            <source type="image/webp" :srcset="img == null ? '/img/challenges.jpg' : $imgRoute + 'challenges/'+alias+'/'+img | webpAdd ">
            <source type="image/png" :srcset="img == null ? '/img/challenges.jpg' : $imgRoute + 'challenges/'+alias+'/'+img | thumb">
            <source type="image/jpg" :srcset="img == null ? '/img/challenges.jpg' : $imgRoute + 'challenges/'+alias+'/'+img | thumb">
            <source type="image/jpeg" :srcset="img == null ? '/img/challenges.jpg' : $imgRoute + 'challenges/'+alias+'/'+img | thumb">
            <img style="width: 300px; height: 150px" :src="img == null ? '/img/challenges.jpg' : $imgRoute + 'challenges/'+alias+'/'+img | webpAdd" alt="Cahllenge preview">
        </picture>
    </div>
</template>

<script>
    export default {
        name: "Challenge",
        props:['classList','title','alias','img','styles']
    }
</script>


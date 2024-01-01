<template>
    <div class="my-3">
        <h5>{{title}}</h5>
        <img  class="w-100" v-for="(img,key) in images" :src="img" :key="key">
    </div>
</template>


<script>

export default({
    name:"PreviewViewer",
    props:[
        'title',
        'images'
    ]
})
</script>

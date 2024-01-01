<template>
    <div class="search__counts">
        <ul class="list-unstyled d-flex flex-column">
            <li><a @click="linked" :href="'/store?title='+text">Store - {{counts.products_count | digit}}</a></li>
            <li><a @click="linked" :href="'/gallery?title='+text">Gallery - {{counts.gallery_count | digit}}</a></li>
            <li><a @click="linked" :href="'/blog?title='+text">Blog - {{counts.blogs_count | digit}}</a></li>
            <li><a @click="linked" :href="'/search-forum/'+text">Forum - {{counts.topics_count | digit}}</a></li>
        </ul>
    </div>
</template>

<script>
export default {
    name:"Counts",
    props:['counts',"text"],
    filters:{
        digit(value){
            return value.toString().replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1.");
        }
    },
    methods:{
        linked(e){
            document.location.href=e.target.getAttribute("href")
        },
    }
}
</script>

<style scoped>

</style>
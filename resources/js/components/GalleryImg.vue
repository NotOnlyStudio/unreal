<template>
    <div class="image">
        <picture>
            <!-- <source type="image/webp" :srcset="path | webpAdd"> -->
            <source type="image/png" :srcset="path | prefix">
            <source type="image/jpg" :srcset="path | prefix">
            <source type="image/jpeg" :srcset="path | prefix">
            <b-img-lazy :src="path | prefix" :alt="alt ? alt : 'None' " />
        </picture>
    </div>
</template>

<script>
// import filters from "../Mixins/ImgSrcFilters";
    export default {
        name: "GalleryImg",
        props:["path","alt"],
        // mixins:[filters],
    }
</script>


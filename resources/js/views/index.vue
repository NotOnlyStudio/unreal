<template>
    <div class="container">
            <div class="d-flex  content-right double-column">
                <div class="container pt-2">
                    <p class="block-title">New models</p>

                    <cards :server-data="productsData"></cards>
                </div>
            </div>
        <div class="container mt-5">
            <p class="block-title">New art</p>
            <div class="w-100">
                <gallery :server-data="galleryData"/>
            </div>
        </div>
        <div class="container mt-4">
            <p class="block-title">New blogs</p>

            <blog :server-data="blogsData ? blogsData : null" />

        </div>
    </div>
</template>

<script>
    import blog from '../components/IndexContainer/BlogsContainer';
    import cards from "../components/IndexContainer/CardsSubblock";
    import gallery from "../components/IndexContainer/GalleryContainer";
    import Challenges from "../components/Challenges/ChallengesWrapper";
    export default {
        name: "index",
        components:{
            blog,
            cards,
            gallery,
            Challenges
        },
        props:[
            "blogsData",
            "productsData",
            "galleryData"
        ],

    }
</script>

<template>
    <div class="card card-gallery">
        <div class="bookmark" title="add to bookmarks">
            <svg xmlns="http://www.w3.org/2000/svg" width="15.725" height="29.008" viewBox="0 0 15.725 29.008">
                <g id="Path_1" data-name="Path 1" transform="translate(0.063)" fill="#fff">
                    <path d="M 0.4400043785572052 27.97186088562012 L 0.5027451515197754 1.935056209564209 C 0.5027451515197754 1.143226265907288 1.21508514881134 0.4999962449073792 2.090665102005005 0.4999962449073792 L 13.57419490814209 0.4999962449073792 C 14.44977474212646 0.4999962449073792 15.1621150970459 1.143226265907288 15.1621150970459 1.933856248855591 L 15.1621150970459 27.94050979614258 L 8.317615509033203 22.2385368347168 L 8.005515098571777 21.97853660583496 L 7.687105178833008 22.23077583312988 L 0.4400043785572052 27.97186088562012 Z" stroke="none"/>
                    <path d="M 2.090664863586426 0.9999961853027344 C 1.490785598754883 0.9999961853027344 1.002745628356934 1.418926239013672 1.002745628356934 1.93626594543457 L 0.9424943923950195 26.93590545654297 L 7.376635551452637 21.83884620666504 L 8.013444900512695 21.33436584472656 L 8.637655258178711 21.8543758392334 L 14.66210556030273 26.87319564819336 L 14.66210556030273 1.933856964111328 C 14.66210556030273 1.418926239013672 14.17407512664795 0.9999961853027344 13.57419490814209 0.9999961853027344 L 2.090664863586426 0.9999961853027344 M 2.090664863586426 -3.814697265625e-06 L 13.57419490814209 -3.814697265625e-06 C 14.72731494903564 -3.814697265625e-06 15.66210556030273 0.8658161163330078 15.66210556030273 1.933856964111328 L 15.66210556030273 29.00781631469727 L 7.997585296630859 22.62269592285156 L -0.06250476837158203 29.00781631469727 L 0.002745628356933594 1.933856964111328 C 0.002745628356933594 0.8658161163330078 0.937535285949707 -3.814697265625e-06 2.090664863586426 -3.814697265625e-06 Z" stroke="none" fill="#9c9c9c"/>
                </g>
            </svg>
        </div>
        <img v-if="image != ''"  :src="image == '#' ? '/assets/1render_small.jpg' : image" class="card-img-top" alt="Card example">

        <img v-else src="/assets/1render_small.jpg"  class="card-img-top" alt="Lamp">

        <div class="card-body">
            <h5 class="card-title">{{cardTitle.length > 50 ? cardTitle.slice(0,45)+"..." : cardTitle }}</h5>
            <div class="d-flex align-items-center justify-content-between">
                <p class="author mb-0">author: <a :href="author">{{author}}</a></p>
                <RatingWrapper
                    :rating-minus="ratingMinus"
                    :rating-plus="ratingPlus"
                />
            </div>
        </div>
    </div>
</template>

<script>
    import RatingWrapper from "../Rating/RatingWrapper";
    export default {
        name: "SimpleCard",
        props:['url','image','cardTitle','author','ratingPlus','ratingMinus','alt'],
        mounted(){
            console.log(this.image + "---")
        },

        components:{
            RatingWrapper
        }
    }
</script>


<template>
        
    <div :class="[ this.uncolumn == null ? 'flex-column' : 'justify-content-end','d-flex align-items-end counts']" v-if="loaded">
        <plus
            :count="plus"
            :type="type"
            :item-id="itemId"
            :assessment = "assessmentValue"
            @changeRating="newRating"
        />
        <minus
            :count="minus"
            :type="type"
            :item-id="itemId"
            :assessment = "assessmentValue"
            @changeRating="newRating"
        />
    </div>
</template>

<script>
    import plus from "./Plus";
    import minus from "./Minus"
    export default {
        name: "RatingWrapper",
        props:[
            'ratingPlus',
            'ratingMinus',
            "type",
            "itemId",
            "assessment",
            "authorId", 
            "itemContent",
            'uncolumn'
        ],
        data(){
            return{
                plus:this.ratingPlus,
                minus: this.ratingMinus,
                loaded: true,
                assessmentValue: this.assessment,
            }
        },
        methods:{
            rerender(){
                this.loaded = false;
                this.$nextTick(() => {
                    this.loaded = true;
                })
            },

            newRating(data){
                axios.post("/rating-post",
                {
                    "type":this.type,
                    "itemId":data.itemId,
                    "ratingType":data.ratingType,
                    "author":this.authorId,
                    "content":this.itemContent,
                })
                .then(
                    resp => {
                        if(resp.status == 203){
                            document.location.href="/login"
                        }
                        if(this.assessmentValue){
                            if(resp.data.like == true){
                                this.plus++;
                                if(this.assessmentValue.like == false)
                                    this.minus --
                            }
                            if(resp.data.like == false){
                                this.minus++;
                                if(this.assessmentValue.like == true)
                                    this.plus --
                            }
                            if(resp.data.like == null){
                                if(this.assessmentValue.like != false){
                                    this.plus --
                                }
                                if(this.assessmentValue.like != true){
                                    this.minus --
                                }
                            }
                        }else{
                            if(resp.data.like == false)
                                this.minus++;
                            if(resp.data.like == true)
                                this.plus++;
                        }

                        this.assessmentValue = {
                            'like':resp.data.like
                        }
                        this.rerender();
                    }
                )
            },
        },
        components:{
            plus,
            minus
        },

    }
</script>


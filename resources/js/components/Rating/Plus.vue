<template>
    <div :class="[ userAssessment == true ? 'active' : '' ,'d-flex plus']" @click.prevent="newPlus">
        <span>{{likesCount == undefined ? 0 : likesCount}}</span>
        <div class="plus__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 6 6">
                <g id="Group_14" data-name="Group 14" transform="translate(-1048.5 -2226.5)">
                    <line id="Line_1" data-name="Line 1" x2="6" transform="translate(1048.5 2229.5)" fill="none" stroke="#ff8562" stroke-width="1.5"></line>
                    <line id="Line_2" data-name="Line 2" y2="6" transform="translate(1051.5 2226.5)" fill="none" stroke="#ff8562" stroke-width="1.5"></line>
                </g>
            </svg>
        </div>
    </div>
</template>

<script>


    export default {
        name: "Plus",
        props:['count','type','itemId',"assessment"],
        data(){
            return{
                likesCount: this.count,
            
            }
        },
        computed:{
             userAssessment: function(){
                if(this.assessment != null && this.assessment.like == 1){
                    return true
                }else{
                    return false;
                }

            }
        },
        methods:{
            newPlus(e){
                e.preventDefault();
                let user = $cookies.get("user")

                if(user){
                    this.$emit("changeRating",{
                        "type":this.type,
                        "itemId":this.itemId,
                        "user_id":user.id,
                        "ratingType":1,
                    })
                }else{
                    document.location.href="/login"
                }
               
            }
        },
        mounted(){


        }

    }
</script>


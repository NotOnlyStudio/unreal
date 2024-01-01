<template style="">
    <preloader v-if="loading"/>
    <div v-else class="d-flex payment__wrapper justify-content-center py-4">
        <div class="d-flex flex-column" style="width: 100%;">
<!--            <basket-selector :standart-price="standartPrice" @change-counts="newCount" />-->
            <basket-selector :standart-price="3" :course="course" @change-counts="newCount" />
        </div>

        <!-- <payment-form v-if="auth" :counts="counts" :user-info="auth" /> -->
        <!-- <login-form v-else /> -->
    </div>
</template>


<script>
import preloader from "../preloader"
import LoginForm from '../LoginForm.vue'
const loginform  = () => import("../LoginForm")
import BasketSelector from "./BasketSelector"
import form from "../Payment/PaymentWrapper"
export default {
    name:"BasketWrapper",
    components:{
        preloader,
        "login-form":loginform,
        "basket-selector":BasketSelector,
        "payment-form":form,
    },
    data(){
        return{
            loading: false,
            counts: 1,
            productsList: this.products,
        }
    },
    methods:{
        newCount(data){
            this.counts = data.counts
        },
        deleteItem(data){
            this.productsList.splice(this.productsList.indexOf(data.product),1);
        }
    },
    props:['products','auth','standartPrice', 'course'],
    mounted(){
        this.$store.dispatch("setChallengeStyles",`display: none`)

    }

}
</script>

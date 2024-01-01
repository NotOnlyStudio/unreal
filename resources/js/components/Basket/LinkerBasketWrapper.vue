<template>
  <preloader v-if="loading"/>
  <div v-else class="d-flex payment__wrapper justify-content-center py-4">
    <div class="d-flex carts flex-column" style="
    background-color: #F2F2F2;
    height: 550px;
    width: 400px;
    padding: 50px;
    border-radius: 30px;">
      <!--            <basket-selector :standart-price="standartPrice" @change-counts="newCount" />-->
      <basket-selector :standart-price="3" @change-counts="newCount" />
    </div>
    <!-- <payment-form v-if="auth" :counts="counts" :user-info="auth" /> -->
    <!-- <login-form v-else /> -->
  </div>
</template>


<script>
import preloader from "../preloader"
import LoginForm from '../LoginForm.vue'
const loginform  = () => import("../LoginForm")
import BasketSelector from "./LinkerBasketSelector"
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
  props:['products','auth','standartPrice'],
  mounted(){
    this.$store.dispatch("setChallengeStyles",`display: none`)

  }

}
</script>

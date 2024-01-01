<template>
    <form v-if="!thanks"  class="payment__wrapper payment-form position-relative">
      <b-alert v-if="haveError" variant="danger" show>{{errorMsg}}</b-alert>
      <preloader-element styles="position: absolute;height:100%;" v-if="loading"/>
      <div class="paypal-container">
        <div class="name">
          <input
            type="text"
            placeholder="Name"
            name="name"
            id=""
            v-model="name"
            class="form-control name__inp"
          />
        </div>
        <div class="surname">
          <input
            type="text"
            placeholder="Surname"
            name="surname"
            v-model="surname"
            id=""
            class="form-control surname__inp"
          />
        </div>
        <div class="birthday position-relative">
          <b-form-datepicker class="position-absolute" :max="minDate" v-model="birthday" locale="en" id="birthday_p"></b-form-datepicker>
          <label
            type="text"
            for="birthday_p"
            class="form-control birthday__inp position-absolute"
            style="height: 44px"
            v-text="birthday == null ? 'Birthday' : birthday"
          />
        </div>
        <div class="country">
          <select v-model="country" name="country" id="" class="country form-control">
            <option value="country" selected="selected" disabled="disabled">
              Country
            </option>
            <option v-for="(item,key) in countries" :key="key" :value="item">{{item}}</option>
          </select>
        </div>
        <div class="email">
          <input
            type="text"
            v-model="email"
            name="email"
            placeholder="Your e-mail"
            class="email__inp form-control"
          />
        </div>
  <stripe-checkout
      ref="checkoutRef"
      mode="payment"
      :pk="stripeKey"
      :line-items="lineItems"
      :success-url="successURL"
      :cancel-url="cancelURL"
      @loading="true"
    />
        <div class="payment_area">
          <div class="price"><span>{{counts}}</span><span>USD</span></div>
          <button class="btn btn-bordered" @click.prevent="payFormConfirm">Pay</button>
        </div>

      </div>
    </form>
    <payment-thanks :payment-info="paymentInfo" v-else/>
</template>


<script>
import PaymentHeader from "./PaymentHeader"
import PaymentContent from "./PaymentContent"
import preloader from "../preloader";
import { StripeCheckout,StripeElementCard } from '@vue-stripe/vue-stripe';
// const paypal = () => import("./Forms/PaypalForm")
// const payoneer = () => import("./Forms/PayoneerForm")
// const visa = () => import("./Forms/VisaForm")
// const webmoney = () => import("./Forms/WebmoneyForm")
// const mastercard = () => import("./Forms/MastercardForm")
const PaymentThanks = () => import("./PaymentThanks")

export default {
    name:"PaymentWrapper",
    components:{
        "payment-header":PaymentHeader,
        "payment-content":PaymentContent,
        "preloader-element":preloader,
        "payment-thanks":PaymentThanks,
        StripeCheckout,
        "stripe-card":StripeElementCard
    },
    data(){
        return{
          loading: false,
          stripeKey: 'pk_test_51IfcA5B6Pg0b0VT8UZQ5KxeZhoBbGKpuRtl1ATTuTwfnhcxnAvkVC3RsiMc8ev3d3TwAeoqUdT8ZiMY4Mnh3LvCk00HurO4XRX', // test key, don't hardcode
      successURL: 'your-success-url',
      cancelURL: 'your-cancel-url',
            lineItems: [
        {
          price: 'some-price-id', // The id of the one-time price you created in your Stripe dashboard
          quantity: 1,
        },
      ],
            thanks: false,
            name:"",
            paymentInfo: [],
            surname:"",
            haveError: false,
            errorMsg:"",
            email:"",
            loading: false,
            cardNumber:"",
            cardInfo: null,
            country:"country",
            currentContent: 0,
            countries:["Australia", "Austria", "Belgium", "Brazil", "Bulgaria", "Canada", "Cyprus", "Czech Republic", "Denmark", "Estonia", "Finland", "France", "Germany", "Greece", "Hong Kong", "Hungary", "India", "Ireland", "Italy", "Japan", "Latvia", "Lithuania", "Luxembourg", "Malaysia", "Malta", "Mexico", "Netherlands", "New Zealand", "Norway", "Poland", "Portugal", "Romania", "Singapore", "Slovakia", "Slovenia", "Spain", "Sweden", "Switzerland", "United Kingdom", "United States"],
            birthday: null,
        }
    },
    computed:{
      minDate(){
        let now = new Date()
        now = new Date(now.getFullYear()-12, now.getMonth(), now.getDate())
        console.warn(now)
        return now
      }
    },
    methods:{
        changeForm(data){
            this.currentContent = data.id
        },
        pay () {
          // ref in template
          const groupComponent = this.$refs.elms
          const cardComponent = this.$refs.card
          // Get stripe element
          const cardElement = cardComponent.stripeElement

          // Access instance methods, e.g. createToken()
          groupComponent.instance.createToken(cardElement).then(result => {
            // Handle result.error or result.token
          })
        },
        payFormConfirm(){
          if(this.email != "" && this.name != "" && this.country != ""){
            window.axios.get("make-payment",{
              "email":this.email,
              "name": this.name + " " + this.surname,
              "country": this.country,
              "counts":this.counts,
            })
            .then(
              resp => {
                console.log(resp)
                if(resp.status === 200){
                  // window.location.replace(resp.data.link)
                }
              }
            )
          }
         
        }
    },
    props:["userInfo","counts"],
    mounted(){
        // var stripe = Stripe('pk_test_51IfcA5B6Pg0b0VT8UZQ5KxeZhoBbGKpuRtl1ATTuTwfnhcxnAvkVC3RsiMc8ev3d3TwAeoqUdT8ZiMY4Mnh3LvCk00HurO4XRX');
    }
}
</script>


<style scoped>
    .payment__wrapper{
        padding-left: 50px;
        width: 100%;
        max-width: 600px;
    }
</style>
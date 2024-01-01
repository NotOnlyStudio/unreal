<template>
  <ul id="myTab" class="nav nav-tabs">
    <payment-element 
        v-for="(item,key) in paymentItems"
        :key="key"
        :item-name="item"
        :item-id="key"
        :class-name="currentContent == key ? 'active '+item : item"
        @changeContentWrapper="changeContent"
    />
  </ul>
</template>


<script>
import PaymentElement from "./PaymentElement"
export default {
  name: "PaymentHeader",
  props: ["currentContent"],
  components:{
      "payment-element":PaymentElement
  },    
  methods:{
      changeContent(data){
          this.$emit("change-form",{
            "id":data.id
          });
      }
  },
  data(){
    return{
        paymentItems:[
            "visa",
            "mastercard",
            "paypal",
            "webmoney",
            "payoneer"
        ]
    }
  }
};
</script>
<template>
  <div
    id="paypal"
    role="tabpanel"
    aria-labelledby="contact-tab"
    class="tab-pane fade active show"
  >
    <div class="paypal-container">
      <div class="name">
        <input
          type="text"
          placeholder="Name"
          name=""
          id=""
          class="form-control name__inp"
        />
      </div>
      <div class="surname">
        <input
          type="text"
          placeholder="Surname"
          name=""
          id=""
          class="form-control surname__inp"
        />
      </div>
      <div class="birthday">
        <input
          type="text"
          placeholder="Birthday"
          name=""
          id=""
          class="form-control birthday__inp"
        />
      </div>
      <div class="country">
        <select name="" id="" class="country form-control">
          <option value="country" selected="selected" disabled="disabled">
            Country
          </option>
          <option value="USA">USA</option>
          <option value="Australia">Australia</option>
        </select>
      </div>
      <div class="email">
        <input
          type="text"
          placeholder="Your PayPal e-mail"
          class="email__inp form-control"
        />
      </div>
      <div class="payment_area">
        <div class="price"><span>9.00</span><span>USD</span></div>
        <button class="btn btn-bordered" @click="payPalSubmit">Pay</button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
    name:"PaypalForm",
    methods:{
        payPalSubmit(){
            window.axios("https://sandbox.cardpay.com/api/auth/token")
            .then(
                resp=> {
                    console.clear()
                    console.log(resp)
                }
            )
        }
    }
}
</script>
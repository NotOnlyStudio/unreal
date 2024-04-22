<template>
  <form method="POST" action="/stripe/checkout/create" class="d-flex flex-column justify-content-between" style="height: 100%">
    <div>
      <span><a href="unreal.shop">UnrealShop</a> > Basket</span>
      <h2 style="font-size: 26px; color: #b3b3b3">Cart</h2>
    </div>
    <div>
      <div class="d-flex flex-lg-row align-itesm-center flex-column ranges justify-content-between">
        <input type="text" :value="rangePrice" disabled>
        <input type="hidden" name="_token" id="csrf-token" :value="csrf" />
        <div class="d-flex align-items-center flex-lg-row flex-column my-lg-0 my-2">
          <label for="numbers" class="text-bold fsz17 text-gray mx-lg-3 mx-0">Count models: </label>
          <input v-model="rangeValue" name="models_count" id="numbers" @change="sendCounts" min="1" type="number">
        </div>
      </div>
      <input type="range" class="range my-4" @change="sendCounts" min="1" max="1000" step="1"  v-model="rangeValue" id="basketRange">
    </div>
    <div class="d-flex justify-content-end">
      <b-button type="submit" variant="bordered sm">Buy models</b-button>
    </div>
  </form>
</template>


<script>
export default {
  name:"BasketSelector",
  props:['standartPrice', 'user-info'],
  data(){
    return{
      rangeValue: 1,
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    }
  },
  methods:{
    sendCounts(){
      this.$emit("change-counts",{
        "counts":this.rangeValue*this.standartPrice
      })
    }
  },
  computed:{
    rangePrice(){
      console.log(this.standartPrice)
      return this.rangeValue*this.standartPrice+"$"
    }
  }
}
</script>


<style scoped>
.ranges input{
  max-width: 75px;
  overflow: hidden;
  border: 1px solid rgba(255,133,98,.5);
  padding: 3px;
  background-color: white;
  border-radius: 5px;
}
.range::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  border-radius: 50%;
  width: 15px;
  height: 15px;
  background:#ff8562;
  cursor: pointer;
}

.range::-moz-range-thumb {
  width: 15px;
  height: 15px;
  background: #ff8562;
  border-radius: 50%;
  cursor: pointer;
}
.range{
  -webkit-appearance: none;
  width: 100%;
  height: 3px;
  background: #ff8562;
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s;
  transition: opacity .2s;
}
#basketRange,.range{
  position: relative;
}
#basketRange::before{
  content: "";
  position: absolute;
  width: 15px;
  height: 15px;
  left: 0;
  background: white;
  border: 2px solid #ff8562;
  border-radius: 50%;
  top: -6px;
  z-index: -1;
}
#basketRange::after{
  content: "";
  position: absolute;
  width: 15px;
  height: 15px;
  z-index: -1;
  right: 0;
  background: white;
  border: 2px solid #ff8562;
  border-radius: 50%;
  top: -6px;
}
</style>

<template>
    <li :class="[itemName,' nav-item']" @click="changeContent(itemId)">
      <button
        id="visa-tab"
        :class="[className,' nav-link']"
      >
        <img :src="'/img/icons/'+itemName+'.svg'" alt="Visa" />
      </button>
    </li>
</template>


<script>
export default {
    name:"PaymentElement",
    props:[
        "itemId",
        "itemName",
        "className"
    ],
    methods:{
        changeContent(id){
            this.$emit("changeContentWrapper",{
                "id":id
            })
        }
    }
}
</script>
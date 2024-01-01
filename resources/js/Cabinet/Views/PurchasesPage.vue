<template>
    <div class="w-100 cards">
        <purchase-card
            v-for="(card, key) in cards"
            :key="key"
            :card-info="card"
        />
    </div>
</template>


<script>
import pagination from 'laravel-vue-pagination'
import PurchaseCard from "../Components/PurchaseCard"
import preloader from "../../components/preloader"

export default {
    name:"PurchasesPage",
    props:["products"],
    components:{
        pagination,
        "purchase-card":PurchaseCard,
        preloader
    },
    data(){
        return{
            loaded: false,
        }
    },
    computed:{
        cards(){
            let cards = [];
            if(this.products.data)
            {
                this.products.data.forEach(
                    item => {
                        cards.push(item.product)
                    }
                )
            }
            else{
                cards = this.products
            }
            return cards
        }
    },    
}
</script>
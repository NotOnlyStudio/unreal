<template>
    <div class="user-purchases">
        <div class="d-flex flex-column">
            <div class="user-purchases__header">
                <p>model</p>
                <p>amount</p>
                <p>Income</p>
            </div>
            <div class="user-purchases__content pt-2">
                <div
                    v-for="(model,key) in models"
                    :key="key"
                >
                    <span>{{model.product.title}}</span>
                    <span>{{model.count}}</span>
                    <span>{{ formatPrice(model.bill) }} USD</span></div>
            </div>
            <p class="user-purchases-total ">
                Summary: <span>{{ summary }} USD</span>
            </p>
        </div>
    </div>

</template>

<script>
export default {
    name: "PurchasesBlock",
    props: ["models", 'summary'],
    computed: {
        totallyPrice() {
            let price = 0;
            this.models.forEach(item => {
                price += item.bill;
            });
            return price;
        }
    },
    methods: {
        formatPrice(price) {
            return price.toFixed(1);
        }
    },
    mounted() {
        console.clear();
        console.log(this.models);
    }
}
</script>

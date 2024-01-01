<template>
    <div class="d-flex carts__item position-relative">
        <picture>
            <!-- <source type="image/webp" :srcset="'/storage/app/public/models/user-' + userId + '/' + productImage | webpThumb" /> -->
            <source type="image/png"
                    :srcset="'/storage/app/public/models/user-' + product.user_id + '/' + productImage | thumb"/>
            <source type="image/jpg"
                    :srcset="'/storage/app/public/models/user-' + product.user_id + '/' + productImage | thumb"/>
            <source type="image/jpeg"
                    :srcset="'/storage/app/public/models/user-' + product.user_id + '/' + productImage | thumb"/>
            <img
                :src="'/storage/app/public/models/user-' + product.user_id + '/' + productImage | thumb"
                class="card-img-top"
            />
        </picture>

        <p class="title">{{ product.title }}</p>
        <div
            class="d-flex flex-column justify-content-between h-100 align-items-end"
        >
            <button class="close" @click="deleteFromBasket(product.id)">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="13.414"
                    height="13.414"
                    viewBox="0 0 13.414 13.414"
                >
                    <g
                        id="Group_17"
                        data-name="Group 17"
                        transform="translate(-744.793 -820.793)"
                    >
                        <line
                            id="Line_3"
                            data-name="Line 3"
                            x2="12"
                            y2="12"
                            transform="translate(745.5 821.5)"
                            fill="none"
                            stroke="#707070"
                            stroke-linecap="round"
                            stroke-width="1"
                        ></line>
                        <line
                            id="Line_4"
                            data-name="Line 4"
                            y1="12"
                            x2="12"
                            transform="translate(745.5 821.5)"
                            fill="none"
                            stroke="#707070"
                            stroke-linecap="round"
                            stroke-width="1"
                        ></line>
                    </g>
                </svg>
            </button>
            <p class="price">${{ product.price == null ? 0 : product.price }}</p>
        </div>
    </div>
</template>


<script>
export default {
    name: "BasketItem",
    props: ["product"],
    computed: {
        productImage() {
            return JSON.parse(this.product.photos)[0];
        },
    },
    methods: {
        deleteFromBasket(id) {
            this.$store.dispatch("deleteFromBasket", [id, $cookies.get("user")]);
            document.querySelector(".shopBag").classList.add("toggle_animation");
            setTimeout(() => {
                document.querySelector(".shopBag").classList.remove("toggle_animation");
            }, 1500);
            this.$emit("deleteFromBasket", {
                "product": this.product
            });
        },
    },
    mounted() {
        console.clear()
        console.warn(this.productImage);
    }
};
</script>

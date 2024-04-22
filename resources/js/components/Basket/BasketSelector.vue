<template>
    <div>
        <form method="POST" action="/stripe/checkout/create">
            <div class="d-flex flex-lg-row align-items-center flex-column ranges justify-content-between">
                <input type="text" :value="rangePrice" disabled>
                <input type="hidden" name="_token" id="csrf-token" :value="csrf"/>
                <div class="d-flex align-items-center flex-lg-row flex-column my-lg-0 my-2">
                    <label for="numbers" class="text-bold fsz17 text-gray mx-lg-3 mx-0">Count models: </label>
                    <input v-model="rangeValue" name="models_count" id="numbers" @change="sendCounts" min="1"
                           type="number">
                </div>
            </div>
            <input type="range" class="range my-4" @change="sendCounts" min="1" max="1000" step="1" v-model="rangeValue"
                   id="basketRange">
            <div class="d-flex justify-content-end">
                <b-button type="submit" variant="bordered sm" translate="no" v-text="$ml.get('stripe')">Buy models (STRIPE)</b-button>
                <b-button type="button" variant="bordered sm" translate="no" v-text="$ml.get('tinkoff')" @click="tinkoff()">Buy models (Tinkoff)</b-button>
<!--                <button type="button" @click='bepaid("USD")' class="btn btn-bordered sm">Buy models (BEPAID) (USD)</button>-->
<!--                <button type="button" @click='bepaid("RUB")' class="btn btn-bordered sm">Buy models (BEPAID) (RUB)</button>-->
            </div>
        </form>

        <form class="payform-tinkoff" name="payform-tinkoff" id="payform-tinkoff">
            <input class="payform-tinkoff-row" type="hidden" name="terminalkey" value="1713381144663">
            <input class="payform-tinkoff-row" type="hidden" name="frame" value="false">
            <input class="payform-tinkoff-row" type="hidden" name="language" value="ru">
            <input class="payform-tinkoff-row" type="hidden" name="receipt" value="">
            <input class="payform-tinkoff-row" type="hidden" :value="auth.email" name="email" >
            <input class="payform-tinkoff-row" type="hidden" name="amount">
            <input class="payform-tinkoff-row" type="hidden" name="DATA">
            <!--            <button class="payform-tinkoff-row payform-tinkoff-btn" type="button"  @click="tinkoff()">Оплатить</button>-->
        </form>
    </div>
</template>


<script>
import {MLBuilder} from 'vue-multilanguage'

export default {
    name: "BasketSelector",
    props: ['standartPrice', 'course', 'auth'],
    data() {
        return {
            rangeValue: 1,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            uuid: '',
            lang: localStorage.getItem('lang'),
            price: this.rangeValue * this.standartPrice * (this.lang === 'ru' ? this.course : 1) + (this.lang === 'ru' ? "₽" : "$"),
        }
    },
    methods: {
        sendCounts() {
            this.$emit("change-counts", {
                "counts": this.rangeValue * this.standartPrice * 10000
            })
        },

        bepaid(lang) {
            this.uuid = this.generateRandomUUID()
            console.log('false');
            let money = this.rangeValue * this.standartPrice
            axios.get('/bepaid/store?uuid=' + this.uuid + '&money=' + money + '&lang=' + lang)
                .then(res => {
                    let params = {
                        checkout_url: "https://checkout.bepaid.tech",
                        checkout: {
                            iframe: true,
                            test: false,
                            transaction_type: "payment",
                            public_key: "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0aOn8+/96yOi0BIMbErpb1k7JuDEevmg1TD3OLOLYIuYJewa0Wlc0tAgdAwRLBwMvV+EUbM3BCkwVQPNUmqL8hkb6PsXL2wCsGi2RWo1mpWTbrgecJ5nrajDh1QW+UW5NkDj0gXnH4rm1z9OxA4mOwdyFv/1cwBBCXFrOVi6mYNiOZfrLJVUA0ypCAqdqjylCir1bR5JovC0c05hF49sr3pQmR/XXQcnNkl6StxO9eqaz2sxBkdlzj8yCzEsldGx/Xe9Ea8bZspT8g+QTU+CTVHvxDvtjjEY2nVQTAICzmfkFa31ru/itvz57oyUmcmInEKJ3et6tqooJQpLcDKJwQIDAQAB",
                            order: {
                                amount: (lang === 'RUB') ? money * 89 * 100 : money * 100,
                                currency: lang,
                                description: "Replenishment",
                                tracking_id: this.uuid
                            },
                            settings: {
                                notification_url: "https://unreal.shop/bepaid/get",
                                language: (lang === 'RUB') ? 'ru' : 'en'
                            },
                            payment_method: {
                                types: ['credit_card']
                            }
                        },
                        closeWidget: (status) => {

                        }
                    };

                    new BeGateway(params).createWidget();
                })
        },



        generateRandomUUID() {
            return (
                this.generateRandomString(8) + '-' +
                this.generateRandomString(4) + '-4' +
                this.generateRandomString(3) + '-' +
                this.generateRandomString(4) + '-' +
                this.generateRandomString(12)
            );
        },
        generateRandomString(length) {
            let result = '';
            const characters = '0123456789abcdef';
            const charactersLength = characters.length;
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        },

        tinkoff() {
            this.uuid = this.generateRandomUUID()
            let money = this.rangeValue * this.standartPrice

            axios.get('/bepaid/store?uuid=' + this.uuid + '&money=' + money)
                .then(res => {

                    const TPF = document.getElementById("payform-tinkoff");
                    const {description, amount,  email, phone, receipt} = TPF;
                    TPF.querySelector('input[name="amount"]').value = money * 89;
                    TPF.querySelector('input[name="DATA"]').value = 'ID=' + this.uuid;


                    if (receipt) {
                        TPF.receipt.value = JSON.stringify({
                            "Taxation": "patent",
                            "Items": [
                                {
                                    "Name": "Оплата",
                                    "Price": money * 89 * 100,
                                    "Quantity": this.rangeValue,
                                    "Amount": money * 89 * 100,
                                    "PaymentMethod": "full_prepayment",
                                    "PaymentObject": "service",
                                    "Tax": "none",
                                }
                            ]
                        });

                    }


                    pay(TPF);
                })
        }


    },
    computed: {
        rangePrice() {
            return this.rangeValue * this.standartPrice * (this.lang === 'ru' ? this.course : 1) + (this.lang === 'ru' ? "₽" : "$");

        },

    },

    created() {
        // Подписываемся на глобальное событие
        this.$root.$on('localStorageLangUpdated', (newValue) => {
            this.lang = newValue;
        });
    },
}
</script>


<style scoped>
.ranges input {
    max-width: 75px;
    overflow: hidden;
    border: 1px solid rgba(255, 133, 98, .5);
    padding: 3px;
    background-color: white;
    border-radius: 5px;
    text-align: center;
}

.range::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    border-radius: 50%;
    width: 15px;
    height: 15px;
    background: #ff8562;
    cursor: pointer;
}

.range::-moz-range-thumb {
    width: 15px;
    height: 15px;
    background: #ff8562;
    border-radius: 50%;
    cursor: pointer;
}

.range {
    -webkit-appearance: none;
    width: 100%;
    height: 3px;
    background: #ff8562;
    outline: none;
    opacity: 0.7;
    -webkit-transition: .2s;
    transition: opacity .2s;
}

#basketRange, .range {
    position: relative;
}

#basketRange::before {
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

#basketRange::after {
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

.payform-tinkoff {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin: 2px auto;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    max-width: 250px;
}
.payform-tinkoff-row {
    margin: 2px;
    border-radius: 4px;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    -webkit-transition: 0.3s;
    -o-transition: 0.3s;
    transition: 0.3s;
    border: 1px solid #DFE3F3;
    padding: 15px;
    outline: none;
    background-color: #DFE3F3;
    font-size: 15px;
}
.payform-tinkoff-row:focus {
    background-color: #FFFFFF;
    border: 1px solid #616871;
    border-radius: 4px;
}
.payform-tinkoff-btn {
    background-color: #FBC520;
    border: 1px solid #FBC520;
    color: #3C2C0B;
}
.payform-tinkoff-btn:hover {
    background-color: #FAB619;
    border: 1px solid #FAB619;
}

</style>

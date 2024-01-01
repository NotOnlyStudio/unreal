<template>
    <b-jumbotron header="Thank you for your purchase" lead="Payment details">
        <hr>
        <div class="d-flex flex-column payment-details">
            <p>Name: Oleg</p>
            <p>Surname: Valeryanovich</p>
            <p>Email: asd@mail.ru</p>
            <p>Amount: 32</p>
            <p>Price: 32$</p>
            <p>Card type: Visa</p>
        </div>
        <hr>
        <p class="payment-answer">Status: <small>Paid up</small></p>
    </b-jumbotron>
</template>


<script>
export default {
    name:"PaymentThanks",
    props:["paymentInfo"],
}
</script>


<style scoped>
    .jumbotron .display-3{
        font-size: 24px;
        font-family: 'GhothamPro';
    }
    .jumbotron{
        margin-left: auto;
        margin-right: 50px;
    }
    .jumbotron .lead{
        font-size: 16px;
        color: gray;
        font-family: 'GhothamPro';
        text-align: center;
    }
    .payment-details{
        padding-left: 10px;
    }
    .payment-details p{
        font-size: 16px;
        font-family: 'GhothamPro';
    }
    .payment-answer{
        font-family: 'GhothamPro';
        font-size: 18px;
        text-align: right;
    }
</style>
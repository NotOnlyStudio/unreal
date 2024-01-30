<template>
    <div class="d-flex align-items-end justify-content-end w-100">
        <div class="wallet__score mx-3" title="Moneys in your wallet">
            <span>{{ score }}</span>
            <span>USD</span>
        </div>
<!--        <button @click="transferMoney" class="btn btn-red">Transfer</button>-->
        <button type="button" class="btn btn-red" data-bs-toggle="modal" data-bs-target="#exampleModal" >
            Transfer
        </button>
    </div>


</template>

<script>
import axios from "axios";
export default {
    props: ["walletScore", 'count'],
    data() {
        return {
            score: this.walletScore,
        };
    },
    methods: {
        transferMoney(){

        },
    }
}
</script>

<style scoped>
.wallet__score {
    background: #fcfcfc;
    border: 1px solid #c8c7c8;
    box-sizing: border-box;
    border-radius: 4px;
    padding: 0 14px;
    height: 46px;
    user-select: none;
    cursor: pointer;
    display: flex;
    justify-content: space-around;
    align-items: center;
}
.wallet__score span {
    font-weight: 600;
    font-size: 19px;
    line-height: 22px;
    letter-spacing: 0.01em;
}
.wallet__score span:last-child {
    color: #6b6a6b;
    margin-left: 5px;
}
.wallet__score span:first-child {
    color: #f08b6a;
}
</style>

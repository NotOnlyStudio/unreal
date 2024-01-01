<template>
    <div class="bg">
        <b-spinner type="grow" label="Spinning"></b-spinner>

    </div>
</template>

<script>
    export default {
        name: "Spinner",
    }
</script>

<style scoped>
    .bg{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100vh;
        position: fixed;
        z-index: 5;
        left: 0;
        top: 0;
        background: rgba(255,255,255,.5);
    }
</style>

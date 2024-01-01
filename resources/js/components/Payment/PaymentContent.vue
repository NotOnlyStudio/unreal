<template>
    <div class="tab-content" id="myTabContent">
        <slot></slot>
    </div>
</template>


<script>
export default {
    name:"PaymentContent",
    props:[
        "currentContent"
    ]
}
</script>
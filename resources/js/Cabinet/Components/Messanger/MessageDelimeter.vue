<template>
    <p class="today">{{delimeterDay ? delimeterDay : 'Error'}}</p>
</template>
<script>
export default {
    name:"MessageDelimeter",
    props:[
        'delimeterDay'
    ]
}
</script>
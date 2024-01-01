<template>
    <a href="#" v-text="$ml.get('support')">123123123</a>
<!--    <li v-if="type == 1">-->
        <!-- <a :href="href"  translate="no" v-text="$ml.get(name)" @click.prevent.stop="reportWrapper = true" >Account</a> -->
<!--        <a href="#" @click.prevent.stop="reportWrapper = true" @click.prevent.stop="reportWrapper = true"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;" v-text="$ml.get(name)" >Поддерживать</font></font></a>-->
<!--    </li>-->
<!--    -->
<!--    <a v-else v-text="$ml.get(name)" translate="no" :href="href"></a>-->
</template>

<script>

export default {
  watch: {
  },
    name: "Footer",
    props:['name', 'type', 'href'],
    data() {
        return {}
    },

}
</script>

<style scoped>
</style>

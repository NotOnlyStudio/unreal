<template>
    <p v-if="route.length != 0" class="breadcrumbs">
        <a href="/" style="color: #b3b3b3; text-decoration: none">UnrealShop</a> >
        <span v-for="(r,key) in route" :key="key">
            <a style="color: #b3b3b3; text-decoration: none" :href="r.alias">{{r.name}}</a> >
        </span>
        
        <span style="color: #b3b3b3;" class="current">{{current}}</span>
    </p>
</template>

<script>
export default {
    name:"BreadCrumbs",
    data(){
        return{
            route:[],
            current:"",
        }
    },
    mounted(){
      let path = window.location.pathname;
      path = path.split("/")
      path.shift();
      this.current = path.pop();
      this.current = this.current[0].toUpperCase() + this.current.slice(1)
        path.forEach(
            (item,key) => {
                function alias(){
                    // if(key == 0) return "/cabinet"
                    // else{
                        let arr = [];
                        for(let  i = 0; i < key; i++){
                            if(i != 0)
                                arr.push(path[i])
                        }
                        return arr.join("/")
                    // } 
                }
                let path_to = alias()
                this.route.push({
                    "name":item[0].toUpperCase()+item.slice(1),
                    "alias":item == "cabinet" ? "/cabinet" : path_to
                      
                });
            }
        )
    }
}
</script>

<style scoped>
    span::first-letter{
        text-transform: uppercase!important;
    }
</style>
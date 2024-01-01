
<template>
    <div v-if="loaded" class="content-right double-column">
        <div class="container">
            <p class="block-title pt-2">Forum</p>
            <table class="w-100 table-pr" style="margin-top: 53px;">
                <thead>
                <th class="mr-auto d-block">Forum</th>
                <th>Topics</th>
                <th>Posts</th>
                </thead>

                <tablewrapper v-for="(forum,key) in forums" v-if="forum.forums.length != 0" :key="key" :forum="forum"></tablewrapper>


            </table>

        </div>
    </div>
    <div v-else>
        <div class="container">
            <b-skeleton-table
                class="w-100"
                :rows="5"
                :columns="4"
                :table-props="{ bordered: true, striped: true }"
            ></b-skeleton-table>
        </div>
    </div>
</template>


<script>
const pagination = () => import('laravel-vue-pagination')
import tablewrapper from "../../../components/TableWrapper"
export default{
    name:"BookmarksForum",
    components:{
        tablewrapper,
        pagination
    },
    data(){
        return{
            loaded: false,
            forums:[],
        }
    },
    props:[
        "serverData"
    ],
     mounted(){
         if(this.serverData){
             this.forums = this.serverData
             this.loaded = true
             console.clear()
             console.warn(this.serverData)
         }else{
            axios.get("/bookmarks/forum")
            .then(
                resp => {
                    this.forums = resp.data
                    this.loaded = true;
                }
            )
         }

    }

}
</script>

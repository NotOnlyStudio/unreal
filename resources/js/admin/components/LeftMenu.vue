<template>
    <nav class="d-flex flex-column" v-if="loaded">
        <a 
            v-for="(item, key) in menu.menu"
            :key="key"
            :href="item.link"
            :class="[currentPath == item.link ? 'active' : '', item.guarded && item.guarded != '' && item.guarded != userRole ? 'd-none' : '']"
        >
            <b-icon :icon="item.icon"/>
            {{item.title}}
            <span v-if="item.type == 'reports' && count != 0" class="badge badge-danger reports-badge">
                {{count == 0 ? "" : count }}
            </span>
        </a>
    </nav>
</template>


<script>
import axios from "axios"
export default {
    name:"LeftMenu",
    props:["menu","userRole"],
    data(){
        return{
            currentPath: window.location.pathname,
            count: 0,
            loaded: false,
        }
    },

    methods:{
        reportsCount(){
            const resp =  axios.get("/reports-count")
            .then(
                resp => {
                    this.count = resp.data;
                }
            )
        },
        checkRole(){
            let currentPath = this.menu.menu.filter(item => item.link == this.currentPath)[0]
            if(currentPath != undefined){
                if(currentPath.guarded != null && currentPath.guarded != this.$role)
                    document.location.href="/admin/dashboard"
            }

        }
    },
    mounted(){
        this.reportsCount();
        this.loaded = true
    },
    created(){
        this.checkRole();
    }
}
</script>
<template>
    <div class="d-flex container flex-column">
        <ul class="list-unstyled profile-nav d-flex" v-if="loaded">
            <li v-for="(link, key) in links" :key="key" :class="[link.path == current ? 'active' : '','position-relative']" :data-type="link.name" >
                <span v-if="link.countNotifications != null && link.countNotifications != 0 " class="notifications__counter">
                    {{link.countNotifications.toString().length > 2 ? '...' : link.countNotifications}}
                </span>
                <a :href="link.path">{{link.name}}</a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "Nav",
        data(){
            return{
                loaded: true,
                current:window.location.pathname,
                links:[
                    {
                        name:"Notifications",
                        path:"/cabinet/notifications",
                        notifications_label: "notifications_count",
                    },
                    {
                        name:"Messages",
                        path:"/cabinet/messages",
                        notifications_label: "messages_count",
                    },
                    {
                        name:"Forum",
                        path:"/cabinet/forum",
                        notifications_label: "forum_count",
                    },
                    {
                        name:"Blog",
                        path:"/cabinet/blog",
                        notifications_label: "",
                    },
                    {
                        name:"Bookmarks",
                        path:"/cabinet/bookmarks",
                        type:"bookmarks",
                        notifications_label: "",
                    },
                                        {
                        name:"Models",
                        path:"/cabinet/models",
                        notifications_label: "",
                    },
                                        {
                        name:"Gallery",
                        path:"/cabinet/gallery",
                        notifications_label: "",
                    },
                    {
                        name:"Purchase",
                        path:"/cabinet/purchase",
                        notifications_label: "",
                    },
                    {
                        name:"Profit",
                        path:"/cabinet/profit",
                        notifications_label: "profit_count",
                    }
                    
                ]
            }
        },
        mounted(){
            let notifications = this.$store.getters.getNotifications;
            let links = this.links
            Object.entries(notifications).forEach(
                item => {
                    let key = item[0]
                    links.map(
                        elem => {
                            if(elem.notifications_label == key){
                                elem.countNotifications = item[1]
                            }
                            
                        }
                    )
                }
            )
            this.loaded = false
            this.links = links  
            this.$nextTick(
                ()=>{
                    this.loaded = true
                }
            )
        }
    }
</script>


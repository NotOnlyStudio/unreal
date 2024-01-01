<template>
    <div class="d-flex inner__content profile-content">
        <ul class="list-unstyled d-flex flex-column">
            <li class="notifications" :key="key" v-for="(not,key) in notifications">
                <a href="">{{not.sender.name}}</a>
                <p>{{not.message}}</p>
                <span>{{not.message_from}}</span>
            </li>
        </ul>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "Notifications",
        props:[
            "notifications"
        ],
        data(){
            return{
               // notifications:[],
            }
        },
        mounted(){
            if(this.notifications == null){
                axios.get("/notifications-get")
                .then(
                    resp => {
                        this.notifications = resp.data
                    }
                )
            }
        }
    }
</script>


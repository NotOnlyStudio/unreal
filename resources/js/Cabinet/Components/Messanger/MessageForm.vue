<template>
    <form @submit.prevent="send" class="d-flex">
        <textarea 
            type="text" 
            v-model="message" 
            class="form-control message-form"
            title="CTRL+ENTER for line break"
        >
        </textarea>
        <button class="btn btn-red" >send</button>
    </form>
</template>


<script>
export default {
    name:"MessageForm",
    data(){
        return{
            message:"",
        }
    },
    methods:{
        send(){
            if(this.message != ""){
                this.$emit("newMessage",{
                    "message":this.message
                })
                this.message = "";
            }
        },

    }
}
</script>
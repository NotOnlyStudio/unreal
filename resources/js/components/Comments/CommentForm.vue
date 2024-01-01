<template>
    <b-form class="comment" id="comment" @submit.prevent="sendComment">
        <b-alert variant="warning" :show="dismissible" dismissible fade>You can not post comments more than 10 seconds</b-alert>
        <textarea  v-model="comment" name="comment" placeholder="Write your comment here..." ></textarea>

        <button class="btn btn-bordered sm">Send >></button>
    </b-form>
</template>

<script>
    export default {
        name: "CommentForm",
        props:[
            'type',
            'itemId',
            'authorId',
            'commentType'
        ],
        data(){
            return{
                comment:"",
                unblock: true,
                dismissible: false,
            }
        },
        methods:{
            sendComment(){
                this.$emit("sendComment", {
                    "type": this.type,
                    "itemId": this.itemId,
                    "authorId": this.authorId,
                    "comment_body": this.comment,
                    "commentType":this.commentType,
                })
                this.comment = "";
            }
        },

    }
</script>


<template>
    <div class="d-flex inner__content profile-content  profile-forum flex-column big" >
        <div v-for="(item, key) in topics" :key="key">
            <p class="theme__bread">
                {{item.title}} > {{item.topics.title}}
            </p>
            <div class="comment opacity" :key="key" v-for="(comment, key) in item.topics.comments">
                <author
                    :name="comment.user.name"
                    :photo="comment.user.photo"
                    :rank="comment.user.rang.name"
                    :id = "comment.user.id"
                />
                <div class="comment__content w-100">
                    <p class="quote" v-if="comment.parent">Quote >> {{comment.parent.user.name}}</p>
                    <p>{{comment.comment_body}}</p>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    const author = () => import("../../components/Author");
    export default {
        name: "Forum",
        data(){
            return{
                loaded: true,
            }
        },
        props:[
            "topics"
        ],
        components:{
            author
        },
        mounted(){
            console.log(this.topics)
        }
    }
</script>

<style scoped>

</style>

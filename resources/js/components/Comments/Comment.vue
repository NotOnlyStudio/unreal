<template>
    <div v-if="comment.parent == null" @keydown.esc="quoteBool = false" >
        <div class="comment">
            <author
                :photo="comment.user.photo"
                :name="comment.user.name"
                rank="rookie"
                :id="comment.user.id"
            />
            <div class="comment__content">
                <p v-html="commentBody"></p>
            </div>
            <div class="comment__footer">
                <rating
                    :item-id="itemId"
                    type="App\Models\Comment"
                    :rating-plus="ratingPlus"
                    :rating-minus="ratingMinus"
                    :assessment="userAssessment"
                    :author-id="authorId"
                    :item-content="commentBody.substring(0, 120)"
                />
                <button class="btn btn-bordered sm" @click="quoteBool = !quoteBool">Quote &gt;&gt;</button>
            </div>
        </div>
        <quote @commentSend="commentDataSend" :to="id" type="App\Models\Comment" :item-id="itemId" :author="this.$store.getters.userId" v-if="quoteBool" :name="author.name" />
    </div>
    <div class="reply" v-else @keydown.esc="quoteBool = false">
        <author
            :photo="comment.user.photo"
            :name="comment.user.name"
            rank="rookie"
            :id="comment.user.id"
        />
        <div class="comment__reply">
            <p class="comment__reply-to">Quote  &gt;&gt; {{comment.parent.user.name}}</p>
            <div class="comment__reply-content">
                <p><span>“</span>{{comment.parent.comment_body}}<span>”</span>
                </p>
            </div>
        </div>
        <div class="comment answer">
            <div class="comment__content">
                <p v-html="commentBody"></p>
            </div>
            <div class="comment__footer">
                <rating
                    :item-id="itemId"
                    type="App\Models\Comment"
                    :rating-plus="ratingPlus"
                    :rating-minus="ratingMinus"
                    :assessment="userAssessment"
                    :author-id="authorId"
                    :item-content="commentBody.substring(0, 120)"
                />
                <button class="btn btn-bordered sm" @click="quoteBool = !quoteBool">Quote &gt;&gt;</button>
            </div>
        </div>
        <quote :to="id" @commentSend="commentDataSend" type="App\Models\Comment" :item-id="itemId"  v-if="quoteBool" :name="author.name" />
    </div>
</template>
<script>
    const author = () => import("../../components/Author")
    const rating = () => import("../../components/Rating/RatingWrapper")
    const quote =  () => import("./QuoteForm");

    export default {
        name: "Comment",
        props:[
            'comment',
            'type',
            'itemId',
            'userAssessment',
            'authorId',
            'ratingPlus',
            'ratingMinus',
        ],
        components:{
            author,
            rating,
            quote,
        },

        data(){
            return{
                quoteBool:false,
                author: this.comment.user,
                commentBody: this.comment.comment_body,
                id:this.comment.id,
                parentId:this.comment.parent_id,
            }
        },
        methods:{
            quoteRequest(id){
                this.quoteBool = true;
            },
            newComment(data){
                this.$emit("getChildComment", {
                    data
                })
            },
            commentDataSend(data){
                this.$emit("sendComment",data)
                this.quoteBool = false;
            }
        },
        mounted(){
            console.log(this.comment)
        }

    }
</script>

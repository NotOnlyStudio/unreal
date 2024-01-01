<template>
    <div  class="d-flex flex-column comments " style="margin-top: 100px;">
        <comment
            v-if="comments.length != 0 && loaded"
            v-for="(comment, key) in comments"
            :key="key"
            :item-id="comment.id"
            :type="type"
            :rating-plus="comment.likes_count"
            :rating-minus="comment.dislikes_count"
            :comment="comment"
            :author-id="comment.user == null ? 0 : comment.user.id"
            @getChildComment="getChildComment"
            @sendComment = "sendComment"
            :userAssessment="comment.user_assessment"
        />
        <commentForm
            @sendComment = "sendComment"
            @newComment="newComment"
            :author-id="this.$store.getters.userId"
            :item-id="itemId"
            :comment-type="commentsType"
            :type="type"
        />
        <pagination v-if="comments.length != 0 && loaded"  :data="laravelData" @pagination-change-page="getResults">
            <span slot="prev-nav">&lt; Previous</span>
            <span slot="next-nav">Next &gt;</span>
        </pagination>
    </div>
</template>

<script>
    const  commentForm = () => import("./CommentForm")
    const comment = () => import("./Comment");
    const pagination = () => import('laravel-vue-pagination')

    export default {
        name: "CommentsWrapper",
        data(){
            return{
                comments:[],
                laravelData: null,
                loaded: false,
            }
        },
        props:[
            'type',
            'commentsType',
            'itemId',
            'itemUser'
        ],
        methods:{
            newComment(comment){
                this.comments.push(comment.comment)
            },
    
            getChildComment(data){
                let comment = data.data.comment;
                let parrent = null;
                this.comments.forEach(function(item){
                    if(item.id == data.data.parentId) parrent = item
                })
                comment.parrent = parrent;
 
                this.loaded = false;
                 this.comments.push(comment)
                 this.$nextTick(()=>{
                     this.loaded = true
                 })
                location.reload()
            }  ,
            getResults(page = 1) {
                window.axios.get('/comments-get?type='+this.commentsType+'&itemId='+this.itemId+'&page=' + page)
                    .then(resp => {
                        if(resp.data.data && resp.data.data.length != 0){
                            this.loaded = false

                            this.comments = resp.data.data;
                            this.$nextTick(()=>{
                                this.loaded = true
                            })
                            console.log(this.comments)
                            this.laravelData = resp.data;
                        }
                    });
            },


            sendComment(data){
                if($cookies.get("user")){
                    let userId = $cookies.get("user").id;
                    data.authorId = userId;
                    data.commentType = this.type
                    data.itemId = this.itemId
                    data.itemUser = this.itemUser
                    window.axios.post("/comments", data)
                    .then(
                        resp => {
                            if(resp.data.error == null){
                                let comment = resp.data.comment;
                                comment['user'] = resp.data.user
                                if(data.parent){
                                    let parent = null;
                                    this.comments.forEach(item => {
                                        if(item.id == data.parent){
                                            parent = item;
                                        }
                                    })
                                    comment.parent = parent
                                    comment.id = resp.data.id

                                }
                                this.loaded = false
                                comment.likes_count =0;
                                comment.dislikes_count = 0;
                                comment.user_assessment = null
                                if(this.comments.length < 20)
                                    this.comments.push(comment)
                                console.log(this.comments)
                                this.$nextTick(()=>{
                                    this.loaded = true
                                })
                            }else{
                                document.location.href = "/login" 
                            }
                           
                        }
                    )
                }else{
                    document.location.href="/login"
                }
            }
        },
        components:{
            commentForm,
            comment,
            pagination
        },
        mounted() {
            this.getResults();

        }
    }
</script>


<template>
    <div v-if="loaded">
        <div class="alert alert-warning" v-if="comments.length == 0">
            <h3>Comments not found</h3>
        </div>
        <table class="table table-stripped my-3" v-else>
            <thead class="thead-dark">
                <th>ID</th>
                <th>Comment</th>
                <th>Parent Comment id</th>
                <th>User</th>
                <th>Edit</th>
                <th>Delete</th>
            </thead>
            <tbody>
                <tr v-for="(comment,key) in comments" :key="key">
                    <td v-text="comment.id"></td>
                    <td v-text="comment.comment_body.length > 350 ? comment.comment_body.substr(0, 350)+'...' : comment.comment_body"></td>
                    <td v-text="comment.parent ? comment.parent.id : null"></td>
                    <td v-text="comment.user.name"></td>
                    <td>
                        <b-button variant="primary" @click="getEdit(key)">
                            <b-icon-pencil-fill/>
                        </b-button>
                    </td>
                    <td>
                        <b-button variant="danger" @click="deleteComment(key)">
                            <b-icon-x/>
                        </b-button>
                    </td>
                </tr>
            </tbody>
        </table>
        <b-modal @ok="saveComment" ref="my-modal" title="Edit comment">
            <b-form-group
                label="Comment"
                label-for="comment"
            >
                <b-textarea v-model="newData" id="comment"/>
            </b-form-group>
        </b-modal>
        <pagination  :limit="4"
        v-if="laravelData != null"
            :data="laravelData"
            @pagination-change-page="getResults"
        />
    </div>
    <spinner v-else></spinner>
</template>

<script>
    import spinner from "../components/Spinner";
    import pagination from "laravel-vue-pagination";
    import axios from 'axios'
    export default {
        name: "Comments",
        data(){
            return{
                loaded: false,
                comments: [],
                newData:"",
                activeComment: 0,
                laravelData:null,
            }
        },
        components:{
           spinner,
           pagination
        },
        methods:{
            getEdit(key){
                this.newData = this.comments[key].comment_body
                this.activeComment = key
                this.$refs['my-modal'].show()
            },
            saveComment(){
                this.comments[this.activeComment].comment_body = this.newData
                axios.post(`/edit-comment/${this.comments[this.activeComment].id}`,{
                    "text":this.newData
                })
                .then(
                    resp => {
                        this.makeToast("Comment was saved")
                    }
                )
            },
            deleteComment(key){
                let id = this.comments[key].id
                this.comments.splice(key,1)
                axios.delete(`/comment/${id}`)
                .then(
                    resp => {
                        this.makeToast("Comment was deleted")
                    }
                )
            },
            makeToast(message,append = false) {
                this.$bvToast.toast(message, {
                title: 'UnrealShop notification',
                autoHideDelay: 5000,
                appendToast: append,
                variant: "success"
                })
            },
            getResults(page = 1){
                axios.get("/comments-admin?page=" + page)
                .then(
                    resp => {
                      console.log(resp.data)
                        this.comments = resp.data.comments.data;
                        this.laravelData = resp.data.comments
                        this.loaded = true;
                    }
                )
            }
        },
        mounted(){
            this.getResults()
        }
    }
</script>


<template>
    <div v-if="loaded" class="content-right double-column">
        <p class="breadcrumbs">
        <a href="/" style="color: #b3b3b3; text-decoration: none">UnrealShop</a> >
        <a href="/forum" style="color: #b3b3b3; text-decoration: none">Forum</a> >
        {{pathName}}
      </p>
        <div class="container">
            <p class="block-title">Forum</p>
            <table class="w-100 table-pr" style="margin-top: 53px;">
                <thead>
                    <th style="margin-right: 600px;" class="w-50 d-block mr-auto">Topics</th>
                    <th>Views</th>
                    <th>Responses</th>
                    <th>Athor</th>
                </thead>
                <tbody class="upl_topics">
                    <tr   class="">
                        <td colspan="4">
                            <form :action="path+'/create-topic'" method="get">
                                <input type="hidden" name="create" value="topic">
                                <button class="btn btn-bordered add-topic" @click="addTopic">+ New Topic</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
                <TopicsWrapper v-for="(topic, key) in topics" :comments-count="topic.comments_count" :url="path" :key="key" :content="topic" />

            </table>
        </div>
    </div>
    <div v-else>
        <div class="container loaded">
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
    import pagination from "laravel-vue-pagination";
    import TopicsWrapper from "../components/TopicsWrapper";
    import { paginationMethods } from "../Mixins/pagination"
    export default {
        name: "Topics",
        mixins:[paginationMethods],
        data(){
            return{
                loaded: false,
                laravelData:null,
                topics:[],
                path:window.location.pathname,
            }
        },
        computed:{
            pathName(){
                let name =  window.location.pathname.split("/")[2];
                return name[0].toUpperCase()+name.slice(1)
            }

        },
        components:{
            pagination,
            TopicsWrapper,

        },
        props:[
            "serverData"
        ],
        methods:{
            getData(){
                let alias = this.$route.params.alias;
                let params = params.replace(/.*page=(\d+).*$/,"")
                var newUrl=this.paginationUrl(window.location.href,"page",page);
                window.history.pushState("", document.title, newUrl);
                window.axios.get("/api/topics/byparent?parent="+alias)
                .then(resp=>{
                    this.laravelData = resp.data
                    this.topics = resp.data.data
                    console.log(this.topics)

                    document.title = resp.data.data[0].parent.title
                })
            },
            addTopic(){
                document.location.href='/upload-article'
            }
        },
        mounted() {
            if(this.serverData){
                this.topics = this.serverData.data
                this.laravelData = this.serverData
                this.loaded = true;
            }
            else{
                this.getData();
                this.loaded = true;
            }
        }
    }
</script>


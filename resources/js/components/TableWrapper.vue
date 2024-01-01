<template>
    <tbody class="topics">
        <tr class="t-title">
            <td colspan="3" class="title"><img :src="'/storage/app/public/forums/icons/'+forum.icon" alt="">{{forum.title}}</td>
        </tr>
         <tr v-for="sf in forum.forums">
            <td class="forum-title">
                <div class="d-flex">
                   <bookmark
                        :item-id="sf.id"
                        item-type="App\Models\Forum"
                        :checked="sf.user_bookmarks_count == 1 ? true : false"
                   />
                    <a :href="'/forum/'+sf.alias">{{sf.title}}</a>
                </div>
            </td>
            <td>{{sf.topics_count}}</td>
            <td>{{sf.countPosts}}</td>
        </tr>
    </tbody>
</template>

<script>
    const bookmark = () => import("./Bookmark")
    export default {
        name: "TableWrapper",
        props: ['forum'],
        methods:{
            getTopic(item){
                console.log(item)
                return 1;
            }
        },
        components:{
            bookmark
        },
        mounted(){

        }
    }
</script>

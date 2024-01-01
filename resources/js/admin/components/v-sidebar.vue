<template>
    <div class="d-flex rounded-1 bg-dark flex-column admin-sidebar my-3 mx-2" style="border-radius: 5px">
        <ul class="d-flex left-menu flex-column list-unstyled">
            <li><router-link to="/admin"><b-icon-grid1x2-fill/><span class="ml-1">Dashboard</span></router-link></li>
            <li><router-link to="/admin/categories"><b-icon-journal-bookmark-fill/><span class="ml-1">Categories</span></router-link></li>
            <li><router-link to="/admin/models"><b-icon-box></b-icon-box><span class="ml-1">Models</span></router-link></li>
            <li><router-link to="/admin/users"><b-icon-people-fill></b-icon-people-fill><span class="ml-1">Users</span></router-link></li>
            <!-- <li><router-link to="/admin/comments"><b-icon-chat-square-fill/><span class="ml-1">Comments</span></router-link></li> -->
            <li><router-link to="/admin/forum"><b-icon-bookshelf/><span class="ml-1">Forum</span></router-link></li>
            <li><router-link to="/admin/challenges"><b-icon-circle-square/><span class="ml-1">Challenges</span></router-link></li>
            <li><router-link to="/admin/blog"><b-icon-newspaper/><span class="ml-1">Blog</span></router-link></li>
            <li><router-link to="/admin/topics"><b-icon-stickies/><span class="ml-1">Topics</span></router-link></li>
            <li><router-link to="/admin/gallery"><b-icon-camera/><span class="ml-1">Gallery</span></router-link></li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "v-sidebar"
    }
</script>


<style>
    .left-menu a {
        color: white;
        font-size: 18px;
        font-family: Arial;
        transition: .3s linear;
    }
    .left-menu{
        padding: 30px 20px;
        margin: 10px;

    }
    .left-menu a:hover{
        color: gray;
        text-decoration: none;
    }
</style>

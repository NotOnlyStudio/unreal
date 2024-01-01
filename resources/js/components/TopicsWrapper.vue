<template>
    <tbody>
         <tr>
            <td>
                <div class="d-flex forum-title">
                    <a :href="url+'/'+content.alias" class="">{{content.title}}</a>
                </div>
            </td>
            <td>{{content.views}}</td>
            <td>{{commentsCount}}</td>
            <td>{{content.user ? content.user.name  : "None" }}</td>
        </tr>
    </tbody>
</template>

<script>
    export default {
        name: "TopicsWrapper",
        props:['content','url',"commentsCount"],

    }
</script>


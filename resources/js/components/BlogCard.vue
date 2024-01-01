<template>
  <div class="card card-blog">
    <div class="card-header">
      <a
        :href="`/edit${url}`"
        v-if="editBool"
        class="edit-pencil"
      >
        <b-icon-pencil/>
      </a>
      <a class="title" :href="url">{{ cardTitle }}</a>
      <bookmark
        :item-id="cardId"
        :item-type="!type || type == 'undefined'?  standartType : type "
        :checked="checked"
        item-type="App\Models\Blog"
      />
    </div>
    <div class="card-body"  v-html="content"></div>
    <div class="card-footer">
      <p class="author mb-0" v-if="showUser">
        author: <a :href="'/user/'+authorId">{{ authorName }}</a>
      </p>
      <div class="d-flex align-items-center" style="margin-left: auto">
        <div class="mx-3">
          <rating
            :item-id="cardId"
            :type="!type || type == 'undefined' ? standartType : type"
            :rating-plus="ratingPlus"
            :rating-minus="ratingMinus"
            :assessment="userAssessment"
            :author-id="authorId"
            :item-content="cardTitle"
          />
        </div>
        <a :href="url" class="btn btn-bordered" v-if='!inner'> Read </a>
      </div>
    </div>
  </div>
</template>

<script>
const rating = () => import("./Rating/RatingWrapper");
const bookmark = () => import("./Bookmark");

export default {
  name: "BlogCard",
  props: [
    "url",
    "cardTitle",
    "author",
    "authorName",
    "authorId",
    "ratingPlus",
    "ratingMinus",
    "checked",
    "content",
    "userAssessment",
    "showBookmark",
    "cardId",
    'type',
    'inner',
    "showUser",
    "editable"
  ],
  components: {
    rating,
    bookmark,
  },
  computed:{
    editBool(){
      if(this.editable != null){
        return true
      }else{
        return false
      }
    }
  },
  data(){
    return{
      standartType:'App\Models\Blog',
    }
  },
};
</script>


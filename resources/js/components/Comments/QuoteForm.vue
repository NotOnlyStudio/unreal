<template>
  <b-form class="comment quote" id="quote" @submit.prevent="sendComment">
    <p>Quote >> {{ name }}</p>
    <textarea
      v-model="comment"
      name="comment"
      placeholder="Write your quote here..."
    ></textarea>
    <button @click.prevent="sendComment" class="btn btn-bordered sm">
      Send >>
    </button>
  </b-form>
</template>

<script>

export default {
  name: "QuoteForm",
  props: ["to", "name", "itemId", "type"],
  data() {
    return {
      comment: "",
      author: null,
    };
  },
  methods: {
    sendComment() {
        let data = {
              parent: this.to,
              comment_body: this.comment,
              authorId: this.author,
              "commentType":this.type,
              itemId: this.itemId,
        }
        this.$emit("commentSend",data)
    },
  },
};
</script>

<style scoped>
</style>

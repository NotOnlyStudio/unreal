<template>
  <form @submit.prevent="formSubmit" ref="articleForm">
    <div class="container">
      <p class="breadcrumbs">
        Forum > {{forumInfo.title}} > Create topic
      </p>
    </div>
    <div class="d-flex content-right double-column">
      <div class="container mt-4">
        <div class="d-flex flex-column">
          <p class="subtags text-orange">
            Title:
            <input
              name="title"
              v-model="cardTitle"
              placeholder="Please enter name"
              type="text"
              @keypress="inpFilter"
            />
          </p>
          <p class="subtags text-orange">
            Tags:
            <input
              name="tags"
              type="text"
              v-model="tags"
              placeholder="Please enter tags"
            />
          </p>

        </div>
        <div class="mt-4">
           <editor name="content" id="content" api-key="ho2xahb38lo8560uw0zpjbbxg62ko9plmy3vt47sex0p2vgj" :init="init" />
        </div>
        <div class="d-flex justify-content-end my-3">
          <button style="color: #ff8767" class="btn btn-bordered">Post</button>
        </div>
      </div>
    </div>
    <preloader v-if="preloader" />
  </form>
</template>

<script>
const editor  = () => import("@tinymce/tinymce-vue");
const preloader = () => import("./preloader");
import {filters} from "../Mixins/filters"
export default {
  name: "CreateTopic",
  components: {
    editor,
    preloader,
  },
  mixins:[filters],
  data() {
    return {
      preloader: false,
      tags: "",
      cardTitle: "",
      init: {
        height: 500,
        menubar: false,
        plugins: [
          "advlist autolink lists link image charmap print preview anchor",
          "searchreplace visualblocks code fullscreen",
          "insertdatetime media table paste code help wordcount",
          "link image code preview imagetools table lists textcolor hr wordcount",
          "media"
        ],
        images_upload_url: "postAcceptor.php",
        file_picker_types: "image",
        images_upload_handler: function (blobInfo, success, failure, progress) {
          let formdata = new FormData();
          formdata.append("file", blobInfo.blob());
          window.axios.post("/upload-img", formdata).then((res) => {
            if (res.status == 200) {
              console.log(res.data);
              success(res.data.location);
            } else {
              failure("upload failed!");
            }
          });
        },
        toolbar:
          "undo redo | styleselect | forecolor | bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent | link image media",
      },
    };
  },
  methods: {
    formSubmit() {
        this.preloader = true;
        let alias = this.alias;
        let formData = new FormData(this.$refs.articleForm);
        let config = { headers: { "Content-Type": "multipart/form-data" } };
        let content = tinymce.activeEditor.getContent();
        content = content.replace("/http","http",content)
        content = content.replace('src="','src="/',content)
        formData.append("content", content);
        formData.append("alias", alias);
        formData.append("forum",JSON.stringify(this.forumInfo))
        formData.append("user_id",this.userId)
        window.axios.post("/topics", formData, config).then((resp) => {
          this.preloader = false;
          console.log(resp);
          if (resp.status == 200) {
              document.location.href="/cabinet/forum"
          } else {
            alert("The server has detected an error");
            console.warn(resp.data);
          }
        });
      }
    },
  props:[
    "forumInfo",
    "userId",
    'alias'
  ],

};
</script>

<style>
.subtags input {
  border: none;
  margin-left: 10px;
  border-bottom: 1px dotted #b3b3b3;
  outline: none !important;
}
.subtags input::placeholder {
  opacity: 0.5;
}
.upload-model__inner label {
  font-size: 107px;
  color: #b3b3b3;
  font-family: Candara;
  -webkit-transition: 0.3s linear;
  transition: 0.3s linear;
  cursor: pointer;
}
</style>

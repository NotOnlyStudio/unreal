<template>
  <form @submit.prevent="formSubmit" ref="articleForm">
    <div class="container">
      <p class="breadcrumbs">
        <a href="/" style="color: rgb(179, 179, 179); text-decoration: none"
          >UnrealShop</a
        >
        >
        <a href="/blog" style="color: rgb(179, 179, 179); text-decoration: none"
          >Blog</a
        >
        > Upload
      </p>
    </div>
    <div class="d-flex content-right double-column">
      <div class="container mt-4">
        <div class="d-flex flex-column">
          <p class="subtags text-orange">
            Title:
            <input
              @keypress="inpFilter"
              name="title"
              v-model="cardTitle"
              placeholder="Please enter name"
              type="text"
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
          <input
            type="hidden"
            name="user_id"
            :value="this.$store.getters.userId"
          />
        </div>
        <div class="mt-4">
          <editor
            name="content"
            id="content"
            api-key="ho2xahb38lo8560uw0zpjbbxg62ko9plmy3vt47sex0p2vgj"
            :init="init"
          />
        </div>
        <div class="d-flex justify-content-end my-3">
          <button style="color: #ff8767" class="btn btn-bordered">Post</button>
        </div>
      </div>
      <preloader v-if="preloader" />
    </div>
  </form>
</template>

<script>
const editor = () => import("@tinymce/tinymce-vue");
import axios from "axios";
import { filters } from "../Mixins/filters";
const Preloader = () => import("../components/preloader.vue");
const preloader = () => import("../components/preloader");

export default {
  name: "Article-add",
  components: {
    editor,
    preloader,
  },
  mixins: [filters],
  data() {
    return {
      preloader: false,
      tags: "",
      cardTitle: "",
      init: {
        height: 500,
        menubar: false,
        plugins: [
          "media  ",
          "advlist autolink lists link image charmap print preview anchor",
          "searchreplace visualblocks code fullscreen",
          "insertdatetime media table paste code help wordcount",
          "link image code preview imagetools table lists textcolor hr wordcount",
        ],
        images_upload_url: "postAcceptor.php",
        file_picker_types: "image",
        images_upload_handler: function (blobInfo, success, failure, progress) {
          let formdata = new FormData();
          formdata.append("file", blobInfo.blob());
          axios.post("/upload-img", formdata).then((res) => {
            if (res.status == 200) {
              success(res.data.location);
            } else {
              failure("upload failed!");
            }
          });
        },
        toolbar:
          "undo redo | styleselect | forecolor | bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent | link image media  ",
      },
    };
  },
  methods: {
    formSubmit() {
      if(this.title != "" && this.tags != ""){
        let formData = new FormData(this.$refs.articleForm);
        let config = {
          headers: { "Content-Type": "multipart/form-data" },
          onUploadProgress: (onUploadProgress) => {
            console.log(onUploadProgress);
          },
        };
        let content = tinymce.activeEditor.getContent();
        content = content.replace('src="','src="/',content)
        content = content.replace("/http","http",content)
        formData.append("content", content);
        this.preloader = true;
        axios.post("/blog", formData, config).then((resp) => {
          this.preloader = false;

          if (resp.data.success == true) {
            document.location.href = "/cabinet/blog/";
          }
        });
      }else{
        alert("Please fill in all fields")
      }
    },

  },
};
</script>

<style scoped>
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

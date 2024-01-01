<template>
  <div v-if="loaded">
    <b-modal
      size="lg"
      title="Edit article"
      ref="editModal"
      @close="editForm = editFormModel"
      @hide="editForm = editFormModel"
      @ok="saveData"
    >
      <b-form ref="editForm">
        <b-form-group label="Title" label-for="title">
          <b-form-input
            placeholder="Title"
            v-model="editForm.title"
            name="title"
            id="title"
          />
        </b-form-group>
        <b-form-group label="Tags" label-for="tags">
          <b-form-input
            name="tags"
            id="tags"
            placeholder="Tags"
            v-model="editForm.tags"
          />
        </b-form-group>
        <b-form-group label="Content" label-for="content">
          <editor
            v-if="editForm.rerender"
            name="content"
            id="content"
            api-key="ho2xahb38lo8560uw0zpjbbxg62ko9plmy3vt47sex0p2vgj"
            :init="init"
          />
        </b-form-group>
          <hr>
                <b-form-group
                    label="Meta description"
                    label-for="metaDescription"
                >
                    <b-form-textarea placeholder="Description" v-model="editForm.metaDescription"  id="metaDescription" name="metaDescription"></b-form-textarea>
                </b-form-group>
                <b-form-group
                    label="Meta keywords"
                    label-for="metaKeywords"
                >
                    <b-form-textarea placeholder="Keywords" v-model="editForm.metaKeywords"  id="metaKeywords" name="metaKeywords"></b-form-textarea>
                </b-form-group>
      </b-form>
    </b-modal>
    <div v-if="blog.length != 0">
      <table class="table table-stripped">
        <thead class="thead-dark">
          <th>ID</th>
          <th>Title</th>
          <th>Tags</th>
          <th>Look article</th>
          <th>To approve</th>
          <th>Edit</th>
          <th>Delete</th>
        </thead>
        <tbody>
          <tr v-for="(card, key) in blog" :key="key">
            <th>{{ card.id }}</th>
            <th>{{ card.title }}</th>
            <th>{{ card.tags }}</th>
            <th>
              <b-button
                target="_blank"
                variant="success"
                :href="'/blog/' + card.alias"
                ><b-icon-link45deg
              /></b-button>
            </th>
            <th>
              <b-button variant="warning" @click="moderateArticle(key)">
                <b-icon-eye-fill v-if="card.moderate" />
                <b-icon-eye v-else />
              </b-button>
            </th>
            <th>
              <b-button variant="primary " @click="editGet(key)"
                ><b-icon-vector-pen
              /></b-button>
            </th>
            <th>
              <b-button @click="deleteBlog(key)" variant="danger"
                ><b-icon-x
              /></b-button>
            </th>
          </tr>
        </tbody>
      </table>
      <pagination  :limit="4"
        :data="laravelData"
        @pagination-change-page="getData"
      ></pagination>
    </div>
    <div v-else class="d-flex flex-column">
      <b-alert variant="warning" show> Topics not found </b-alert>
    </div>
  </div>
  <spinner v-else />
</template>


<script>
import spinner from "../components/Spinner";
import axios from "axios";
import editor from "@tinymce/tinymce-vue";
import pagination from "laravel-vue-pagination";
export default {
  name: "Topics",
  data() {
    return {
      loaded: false,
      laravelData: {},
      blog: [],
      editForm: {
        content: "",
        title: "",
        tags: "",
        key: "",
        metaDescription:"",
        metaKeywords:"",
        rerender: true,
      },
      init: {
        height: 500,
        menubar: false,
        plugins: [
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
              console.log(res.data);
              success(res.data.location);
            } else {
              failure("upload failed!");
            }
          });
        },
                    init_instance_callback: (item) => {
                        item.setContent(this.editForm.content);
                    },
        toolbar:
          "undo redo | styleselect | forecolor | bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent | link image ",
      },
      editFormModel: {
        content: "",
        title: "",
        tags: "",
                metaDescription:"",
        metaKeywords:"",
        key: "",
        rerender: true,
      },
    };
  },
  components: {
    spinner,
    pagination,
    editor,
  },
  methods: {
    getData(page = 1) {
      this.loaded = true;
      axios.get("/admin-topics?page=" + page).then((resp) => {
        this.blog = resp.data.data;
        this.laravelData = resp.data;
      });
    },
    editGet(key) {
      let info = this.blog[key];
      this.editForm.title = info.title;
      let meta = info.meta != null ? JSON.parse(info.meta) : {description:"",keywords:""}
      this.editForm.metaKeywords = meta.keywords
      this.editForm.metaDescription = meta.description
      this.editForm.tags = info.tags;
      this.editForm.content = info.content;
      this.editForm.rerender = false;
      this.editForm.key = key;
      this.$nextTick(() => {
        this.editForm.rerender = true;
      });
      this.$refs["editModal"].show();
    },
    saveData() {
      let form = new FormData(this.$refs.editForm);
      let key = this.editForm.key;
      let content = tinymce.activeEditor.getContent();
      content = content.replace("/http","http",content)
      content = content.replace('src="','src="/',content)
      form.append("content", content);
      let meta = {
        "keywords":this.editForm.metaKeywords,
        "description":this.editForm.metaDescription,
      }
      form.append("meta",JSON.stringify(meta))
      let config = { headers: { "Content-Type": "multipart/form-data" } };
      form.append("id", this.blog[key].id);
      axios.post("/edit/topic/"+this.blog[key].id, form, config).then((resp) => {
          this.loaded = false;
          this.blog[key] = resp.data.item;
          console.log(this.blog[key])
          console.log(resp.data)
          this.$nextTick(() => {
            this.loaded = true;
          });
      });
    },
    deleteBlog(key) {
      let id = this.blog[key].id;
      axios.delete("/topics/" + id).then((resp) => {
        if (resp.status == 200) {
          this.blog.splice(key, 1);
          this.loaded = false;
          this.$nextTick(() => {
            this.loaded = true;
          });
        }
      });
    },
    moderateArticle(key) {
      let id = this.blog[key].id;
      axios.post("/moderate/topic/" + id).then((resp) => {
        if (resp.status == 200) {
          this.blog[key].moderation = !this.blog[key].moderation;
          this.loaded = false;
          this.$nextTick(() => {
            this.loaded = true;
          });
          this.makeToast(
            `Topic ${
              this.blog[key].moderation ? "approved" : "removed from the show"
            }`
          );
        }
      });
    },
    makeToast(message, append = false) {
      this.$bvToast.toast(message, {
        title: "UnrealShop notification",
        autoHideDelay: 5000,
        appendToast: append,
        variant: "success",
      });
    },
  },
  mounted() {
    this.getData();
  },
};
</script>

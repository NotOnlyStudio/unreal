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
        <b-form-group v-if="editForm.rerender" label="Content" label-for="content" >
          <editor
            v-if="editForm.rerender"
            name="content"
            id="content"
            :key="secrKey"
            api-key="ho2xahb38lo8560uw0zpjbbxg62ko9plmy3vt47sex0p2vgj"
            :init="init"
            :disabled="false"

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

          <b-form-group
              label="First"
              label-for="first"
          >
              <input type="checkbox" id="first" v-model="editForm.first" value="first" :checked="editForm.first" name="first"/>
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
              <b-button variant="success" :href="'/blog/' + card.alias"
                ><b-icon-link45deg
              /></b-button>
            </th>
            <th>
              <b-button
                variant="warning" @click="moderateArticle(key)"
                >
                <b-icon-eye-fill v-if="card.moderation"/>
                <b-icon-eye v-else/>
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
        <b-alert variant="warning" show>
            Articles not found
        </b-alert>
        <b-button variant="primary" href="/upload-article" target="_blank"><b-icon-plus-circle class="mr-2"/> <span class="mx-2">Add article</span></b-button>
    </div>
  </div>
  <spinner v-else />
</template>

<script>
import spinner from "../components/Spinner";
import editor from "@tinymce/tinymce-vue";
import axios from "axios";
import pagination from "laravel-vue-pagination";

export default {
  name: "Blog",
  data() {
    return {
      secrKey: 0,
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
        first:false,
      },
      init:{
        height: 500,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount',
            'link image code preview imagetools table lists textcolor hr wordcount',"media"
        ],
        images_upload_url: 'postAcceptor.php',
            file_picker_types: 'image',
            images_upload_handler:function(blobInfo, success, failure, progress) {
                let formdata = new FormData()

                formdata.append('file',blobInfo.blob())
                axios.post('/upload-img',formdata)
                .then((res) => {
                    if (res.status == 200) {
                        success(res.data.location)
                    } else {
                        failure('upload failed!')
                    }
                })
        },
                    init_instance_callback: (item) => {
                        item.setContent(this.editForm.content);
                    },
        toolbar: 'undo redo | styleselect | forecolor | bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent | link image media'

                },
      editFormModel: {
        content: "",
        title: "",
        tags: "",
        metaDescription:"",
        metaKeywords:"",
        key: "",
        rerender: true,
        rtx: false,
      },
    };
  },
  components: {
    spinner,
    editor,
    pagination,
  },
  methods: {
    getData(page = 1) {
      this.loaded = true;
      axios.get("/admin-blog?page=" + page).then((resp) => {
        console.log(resp)
        this.blog = resp.data.data;
        this.laravelData = resp.data;
      });
    },
    editGet(key) {
      let info = this.blog[key];
      let meta = info.meta != null ? JSON.parse(info.meta) : {description:"",keywords:""}
      this.editForm.metaKeywords = meta.keywords
      this.editForm.metaDescription = meta.description
      this.editForm.title = info.title;
      this.editForm.tags = info.tags;
      this.editForm.content = info.content;
      this.editForm.first = info.first;
      this.editForm.key = key;
      this.secrKey  = key;

      this.$refs["editModal"].show();
      setTimeout(
        () => {
                this.editForm.rerender = false;
      this.$nextTick(() => {
        this.editForm.rerender = true;
      });
        },
        1000
      )
    },
    saveData() {
      let form = new FormData(this.$refs.editForm);
      let key = this.editForm.key;
      let content = tinymce.activeEditor.getContent();
      content = content.replace('src="','src="/');
      content = content.replace("/http","http",content)
      let meta = {
          "keywords":this.editForm.metaKeywords,
          "description":this.editForm.metaDescription,
      }
      form.append("meta",JSON.stringify(meta))
      form.append("content", content);
      let config = { headers: { "Content-Type": "multipart/form-data" } };
      form.append("id", this.blog[key].id);
      axios.post("/edit/blog", form, config).then((resp) => {
        console.log(resp);
        if (resp.data.success == true) {
          this.blog[key] = resp.data.item;
          this.loaded = false;
          this.$nextTick(() => {
            this.loaded = true;
          });
        }
      });
    },
    deleteBlog(key) {
        if(confirm("Do you really want to delete?")) {
            let id = this.blog[key].id;
            axios.delete("/blog-delete/" + id).then((resp) => {
                if (resp.status == 200) {
                    this.blog.splice(key, 1);
                    this.loaded = false;
                    this.$nextTick(() => {
                        this.loaded = true;
                    });
                }
            });
        }
    },
    moderateArticle(key){
        let  id = this.blog[key].id
        axios.post("/moderate/blog/"+id)
        .then(
            resp => {
                if(resp.status == 200){
                    this.blog[key].moderation = !this.blog[key].moderation
                    this.loaded = false;
                    this.$nextTick(() => {
                        this.loaded = true;
                    });
                    this.makeToast(`Blog ${  this.blog[key].moderation ? 'approved' : 'removed from the show'}`)
                }
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
      }
  },
  mounted() {
    this.getData();
    document.addEventListener('focusin', function (e) {
     let closest = e.target.closest(".tox-tinymce-aux, .tox-dialog, .moxman-window, .tam-assetmanager-root");
     if (closest !== null && closest !== undefined) {
          e.stopImmediatePropagation();
     }
});
  },
};
</script>


<style scoped>
  button:disabled{
    cursor: not-allowed;
  }
  .v-dialog__content {z-index: 203 !important;}
.tox-tinymce-aux {
    position: relative !important;
    z-index: 1600;
}
</style>

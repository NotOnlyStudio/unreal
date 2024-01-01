<template>
  <div v-if="loaded">
    <div  v-if="blog.length != 0">
        <table class="table table-stripped">
        <thead class="thead-dark">
            <th>ID</th>
            <th>Title</th>
            <th class="max-width: 200px">Tags</th>
            <th>Photos</th>
            <th>To approve</th>
            <th>Edit</th>
            <th>Delete</th>
        </thead>
        <tbody>
            <tr v-for="(card, key) in blog" :key="key" v-if="card != empty">
            <td>{{ card.id }}</td>
            <td>{{ card.title }}</td>
            <td style="max-width: 200px">
                <p class="w-100" style="word-break: break-all; max-height: 200px">
                {{ card.tags }}
                </p>
            </td>
            <td>
                <b-button variant="success"
                    @click="getPhotos(key)"
                ><b-icon-image-fill /></b-button>
            </td>
            <td>
                <b-button
                    @click="approve(key)"
                    variant="info"
                >
                <b-icon-check-square v-if="card.moderation == false"/>
                <b-icon-check-square-fill v-else/>
                </b-button>
            </td>
            <td>
                <b-button variant="primary" @click="editGet(key)"><b-icon-vector-pen /></b-button>
            </td>
            <td>
                <b-button variant="danger" @click="deleteGallery(key)"><b-icon-x /></b-button>
            </td>
            </tr>
        </tbody>
        </table>
        <pagination
        :data="laravelData"
        @pagination-change-page="getData"
        ></pagination>
        <b-modal v-if="loaded" @ok="saveGallery" @close="editForm = editFormModel" @hide="editForm = editFormModel" title="Edit gallery" ref="editModal">
        <b-form ref="editForm">
            <b-form-group label="Title" label-for="title">
            <b-form-input
                id="title"
                name="title"
                v-model="editForm.title"
                placeholder="Title"
            />
            </b-form-group>
            <input type="hidden" name="gallery_id" :value="editForm.gallery_id">
            <input type="hidden" name="user_id" :value="editForm.user_id">
            <b-form-group label="Tags" label-for="tags">
                <b-form-input
                    id="tags"
                    name="tags"
                    v-model="editForm.tags"
                    placeholder="Tags"
                />
                </b-form-group>
            <b-form-group label="Description" label-for="description">
                <b-form-textarea
                    id="description"
                    name="description"
                    v-model="editForm.description"
                    placeholder="Description"
                />
            </b-form-group>
            <b-form-group label="Photos" label-for="photos">
            <b-form-file
                @change="newExamples"
                multiple
                placeholder="Photos"
                id="photos"
                name="photos[]"
            />
            <preview
                v-if="editForm.photosPreview.length != 0 && editForm.reload"
                title="Photos"
                :images="editForm.photosPreview"
            />
            </b-form-group>

            <div v-if="!videoBoolean" class="pb-5">
                <b-form-group
                    label="Video src"
                    label-for="videoSrc"
                >
                    <b-form-input id="videoSrc" v-model="videoSrc" placeholder="Video src"/>
                    <b-button @click="uploadVideo" variant="primary float-right mt-2">Add video</b-button>
                </b-form-group>
            </div>
            <div style="height: 350px" class="w-100 border border-sm position-relative" v-else>
                <iframe :src="videoSrc" v-if="videoBoolean" frameborder="0" class="w-100 position-absolute h-100"></iframe>
                <b-form-input class="d-none" v-model="videoSrc" id="videoSrc" placeholder="Video src"/>
                <div class="position-absolute delete-bg" v-if="videoBoolean" style="z-index:3">
                    <button class="delete"  @click="videoBoolean = false">
                        <img src="/img/icons/delete.svg" alt="" />
                    </button>
                </div>
            </div>
            <photos
            @deletePhoto="deletePreview"
            title="Gallery images"
            :photos="editForm.photos"
            v-if="editForm.reload && editForm.photos.lengt != 0"
            :prefix="'/storage/app/public/gallery/user-'+editForm.user_id"
            type="photos"
            />
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
            <input name="deletedPhotos" v-model="editForm.deletedPhotos" type="hidden">
        </b-form>
        </b-modal>
        <b-modal ref="photosModal" hide-footer @close="photosHide" @hide="photosHide" title="Photos">
            <photos
                v-if="photos.length != 0 && photosBool"
                title="Gallery images"
                :photos="photos"
                :deletable ="false"
                :prefix="'/storage/app/public/gallery/user-'+user_id"
                type="photos"
            />
        </b-modal>
    </div>
    <div class="d-flex flex-column" v-else>
        <b-alert variant="warning" show>
            Articles not found
        </b-alert>
        <b-button href="/upload-art" variant="primary" target="_blank"><b-icon-plus-circle class="mr-2"/> Add article</b-button>
    </div>
    <div class="mt-4">
            <hr>
            <b-button v-b-toggle.collapse-1 variant="primary"><b-icon-chevron-double-down/><span class="ml-2">Uploads rules</span></b-button>
            <b-collapse id="collapse-1" class="mt-2">
                  <div class="py-2">
                    <editor
                        v-if="loaded"
                        :init="init"
                    />
                    <div class="my-3 d-flex justify-content-end">
                        <b-button variant="success" @click="saveRules('/gallery/save-rules')"><b-icon-download/><span class="ml-2 m-3">Save rules</span></b-button>
                    </div>
                </div>
            </b-collapse>
        </div>
  </div>
  <spinner v-else />
</template>

<script>
import spinner from "../components/Spinner";
import axios from "axios";
import pagination from "laravel-vue-pagination";
import {rules} from "../mixins/rules-mixin"
import editor from '@tinymce/tinymce-vue'
import {upload_video} from "../../Mixins/iframe_video"

const photos = () => import("../components/PohotosPreview");
const preview = () => import("../components/PreviewViewer");
export default {
    name: "Gallery",
    mixins:[rules,upload_video],
  data() {
    return {
        loaded: false,
        laravelData: {},
        blog: [],
        editForm:{
            reload: false,
            title:"",
            tags:"",
            photos:[],
            deletedPhotos:[],
            photosPreview:[],
            key: null,
            metaDescription:"",
            metaKeywords:"",
            deletedPhotos:"",
            description: "",
            user_id: 0,
            gallery_id: 0,
        },
                         init:{

                    height: 500,
                    menubar: false,
                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount',
                        'link image code preview imagetools table lists textcolor hr wordcount'
                    ],
                    init_instance_callback: (item) => {
                        item.setContent(this.rules);
                    },
                    toolbar: 'undo redo | styleselect | forecolor | bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent | link'

                },
        photos:[],
        user_id:0,
        photosBool: false,
        editFormModel:{
            reload: false,
            user_id: 0,
            title:"",
            gallery_id: 0,
            key: null,
            description: "",
            metaDescription:"",
            metaKeywords:"",
            tags:"",
            deletedPhotos:"",
            photos:[],
            photosPreview:[],
        }
    };
  },
  components: {
    spinner,
    pagination,
    photos,
    preview,
    editor
  },
  methods: {
    getPhotos(key){
        let info = this.blog[key]
        this.photos = JSON.parse(info.photos)
        this.user_id = info.user_id
        this.photosBool =true;
        this.$refs['photosModal'].show();
    },
    saveGallery(){
        let form = new FormData(this.$refs.editForm)
        form.append("video",this.videoSrc)
        let meta = {
                "keywords":this.editForm.metaKeywords,
                "description":this.editForm.metaDescription,
            }
        form.append("meta",JSON.stringify(meta))
        let key = this.editForm.key
        let config = { headers: { "Content-Type": "multipart/form-data" } };
        axios.post("/edit/gallery",form, config)
        .then(
            resp => {
                console.log(resp)
                if(resp.status == 200){
                    this.loaded = false
                    this.blog[key] = resp.data.item;
                    this.$nextTick(
                        () => {
                            this.loaded = true
                        }
                    )
                }
            }
        )
    },
    photosHide(){
        this.photos = []
        this.user_id = 0
        this.photosBool = false;
    },
    getData(page = 1) {
      axios.get("/admin-gallery?page=" + page).then((resp) => {
        this.blog = resp.data.data;
        console.clear();
        console.log(resp.data)
        this.laravelData = resp.data;
      });
    },
    deletePreview(data){
        this.editForm.deletedPhotos += this.editForm.deletedPhotos == "" ? data.photo : data.photo+","
        let  index = null;
        let photos = this.editForm.photos;
        for(let i=0;i<photos.length;i++) if(photos[i] == data.photo) index = i
        this.editForm.reload = false
        photos.splice(index,1)
        this.editForm.examples = photos
        this.$nextTick(() => this.editForm.reload = true)
        console.log(this.editForm.examples)
    },
    editGet(key){
        let info = this.blog[key]
        let meta = info.meta != null ? JSON.parse(info.meta) : {description:"",keywords:""}
        this.editForm.metaKeywords = meta.keywords
        this.editForm.metaDescription = meta.description
        this.editForm.title = info.title
        this.editForm.tags = info.tags
        this.editForm.photos = JSON.parse(info.photos)
        this.editForm.user_id = info.user_id
        this.editForm.reload = true
        this.editForm.key = key;
        this.editForm.description = info.description
        this.editForm.gallery_id = info.id
        this.videoSrc = info.video
        this.videoBoolean = info.video ? true : false
        this.$refs['editModal'].show();
    },
    approve(key){
        let id = this.blog[key].id
        axios.post(
            "/approve/gallery/"+id
        )
        .then(
            resp => {
                if(resp.status == 200){
                    this.loaded = false
                    this.blog[key].moderation = !this.blog[key].moderation
                    this.$nextTick(
                        () => {
                            this.loaded = true
                        }
                    )
                }
            }
        )
    },
    newExamples(event){
      this.editForm.photosPreview = [];

      var input = event.target;
        var count = input.files.length;
        var index = 0;
        if (input.files) {
          while(count --) {
            var reader = new FileReader();
            reader.onload = (e) => {
              this.editForm.photosPreview.push(e.target.result);
            }
            reader.readAsDataURL(input.files[index]);
            index ++;
          }
        }
          this.reloader()

    },
    deleteGallery(key){
        let id = this.blog[key].id
        axios.delete("/gallery/"+id).
        then(
            resp => {
                this.loaded = false
                delete(this.blog[key])
                this.$nextTick(
                    () => {
                        this.loaded = true
                    }
                )
            }
        )
    }
  },
  mounted() {
    this.getData();
     axios.get("/api/gallery/get-rules")
    .then(
        resp => {
            console.log(resp)
            this.rules = resp.data.rules ? JSON.parse(resp.data.rules) : []
        }
    )
    this.loaded = true
  },
};
</script>

<style scoped>
.delete-bg{
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      background: rgba(255,255,255,.5);
    }
    .delete-bg button{
      background: none;
      border: none;
      opacity: 0;
      transition: .3s linear;
    }
    .delete-bg:hover button{
      opacity: 1;
    }
    .delete-bg button:hover{
      opacity: 0.9;
    }
</style>

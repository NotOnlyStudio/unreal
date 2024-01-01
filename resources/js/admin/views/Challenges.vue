<template>
  <div v-if="loaded" class="d-flex flex-column">
    <b-modal id="addModal" @ok="addChallenge" title="Add challenge">
      <b-form ref="challengeAddForm">
        <b-form-group label="Challenge title" label-for="title">
          <b-input
            id="title"
            placeholder="Title"
            name="title"
            v-model="addForm.title"
          />
        </b-form-group>
        <b-form-group label="Pick deadline" label-for="deadline">
          <b-form-datepicker
            id="deadline"
            name="deadline"
            v-model="addForm.deadline"
            class="mb-2"
          ></b-form-datepicker>
        </b-form-group>
        <b-form-group label="Description" label-for="description">
          <b-form-textarea
            name="description"
            id="description"
            v-model="addForm.description"
            placeholder="Enter challenge description"
          ></b-form-textarea>
        </b-form-group>
        <b-form-group label="About challenge:" label-for="about">
          <b-form-textarea
            name="about"
            id="about"
            v-model="addForm.about"
            placeholder="Enter about challenge"
          ></b-form-textarea>
        </b-form-group>
        <b-form-group label="Requrements:" label-for="requrements">
          <b-form-textarea
            name="requrements"
            id="requrements"
            v-model="addForm.requrements"
            placeholder="Enter about requrements"
          ></b-form-textarea>
        </b-form-group>
        <b-form-group label="Moderation:" label-for="moderation">
          <b-form-textarea
            name="moderation"
            id="moderation"
            v-model="addForm.moderation"
            placeholder="Enter about moderation"
          ></b-form-textarea>
        </b-form-group>
        <b-form-group label="Choose photo for intro" label-for="image">
          <b-form-file
            v-model="addForm.image"
            name="image"
            id="image"
            accept=".jpg, .png, .gif, .jpeg"
            placeholder="Choose the intro photo"
          ></b-form-file>
        </b-form-group>
        <b-form-group label="Chose photos" label-for="photos">
          <b-form-file
            v-model="addForm.photos"
            accept=".jpg, .png, .gif, .jpeg"
            multiple
            placeholder="Choose photos"
            name="photos[]"
          ></b-form-file>
        </b-form-group>
      </b-form>
    </b-modal>
    <b-modal ref="Photos" size="xl" title="Photos" hide-footer>
      <div class="d-flex flex-column p-3">
        <h4>Intro photo</h4>
        <img :src="'/storage/app/public/challenges/' +editForm.alias+'/'+intro" alt="" />
        <h4 class="mt-3">Examples</h4>
      <img v-for="img in images" :src="`/storage/app/public/challenges/${editForm.alias}/${img}`" alt="" />
      </div>
    </b-modal>
    <b-modal ref="info" title="Info" hide-footer>
      <div class="d-flex flex-column">
        <h5>About:</h5>
        <p>{{ about }}</p>
        <h5>Description:</h5>
        <p>{{ description }}</p>
        <h5>Requirments:</h5>
        <p>{{ requirments }}</p>
        <h5>Moderation:</h5>
        <p>{{ moderation }}</p>
      </div>
    </b-modal>
    <b-modal size="lg" @ok="editSave" ref="editModal" title="Edit challenge" @close="editForm = editFormMask" @hide="editForm = editFormMask">
      <b-form ref="editForm">
        <b-form-group label="Title" label-for="title">
          <b-form-input id="title" name="title" v-model="editForm.title" placeholder="Title" />
        </b-form-group>
        <b-form-group label="Deadline"  label-for="deadline">
          <b-form-datepicker name="deadline" v-model="editForm.deadline" id="deadline" placeholder="Deadline" />
        </b-form-group>
        <b-form-group label="Description" label-for="description">
          <b-form-textarea id="description" name="description" v-model="editForm.description" placeholder="Description" />
        </b-form-group>
        <b-form-group label="About" label-for="about">
          <b-form-textarea id="about" name="about" placeholder="About" v-model="editForm.about" />
        </b-form-group>
        <b-form-group label="Requirments" label-for="requirments">
          <b-form-textarea id="requirments" name="requirments" placeholder="Requirments" v-model="editForm.requirments" />
        </b-form-group>
        <b-form-group label="Moderation" label-for="moderation">
          <b-form-textarea id="moderation" name="moderation" v-model="editForm.moderation" placeholder="Moderation" />
        </b-form-group>
        <b-form-group label="New preview" label-for="preview">
          <b-form-file @change="newPreview" name="preview" placeholder="Preview" id="preview" />
          <preview
            v-if="editForm.introPreview.length != 0 && editForm.reload"
            title="Preview"
            :images = "editForm.introPreview"

          />
        </b-form-group>
        <b-form-group label="New examples" label-for="examples">
          <b-form-file @change="newExamples" multiple placeholder="Examples" id="examples" name="examples[]"/>
          <preview
            v-if="editForm.photosPreview.length != 0 && editForm.reload && editFormMask.loaded"
            title="Photos"
            :images = "editForm.photosPreview"
          />
        </b-form-group>
        <photos
            @deletePhoto = "deletePreview"
            title="Preview"
            :photos="editForm.preview"
            v-if="editForm.reload"
            :prefix="`/storage/app/public/challenges/${editForm.alias}`"
            type="preview"
        />
        <input name="deletedAvatar" v-model="editForm.deletedAvatar" type="hidden">
        <input name="deletedPhotos" v-model="editForm.deletedPhotos" type="hidden">
        <photos 
            @deletePhoto="deletePhoto"
            title="Examples"
            v-if="editForm.reload && editForm.examples.length != 0 && editForm.loaded"
            :photos="editForm.examples"
            :prefix="'/storage/app/public/challenges/'+editForm.alias"
            type="examples"
        />
      </b-form>
    </b-modal>
    <div class="d-flex">
      <b-button v-b-modal.addModal variant="primary"><b-icon-plus-circle class="mr-2"/> Add</b-button>
    </div>
    <b-alert variant="warning" v-if="challenges.length == 0">
      <h3>Challenges not found</h3>
    </b-alert>
    <table class="table table-stripped my-3" v-else>
      <thead class="thead-dark">
        <th>ID</th>
        <th>Deadline</th>
        <th>Name</th>
        <th>See examples</th>
        <th>See info</th>
        <th>Members</th>
        <th>Edit</th>
        <th>Delete</th>
      </thead>
      <tbody>
        <tr v-for="(challenge, key) in challenges">
          <td>{{ challenge.id }}</td>
          <td>{{ challenge.deadline }}</td>
          <td>{{ challenge.name }}</td>
          <td>
            <b-button variant="info" @click="photosGet(key)"
              ><b-icon-image-fill/></b-button>
          </td>
          <td>
            <b-button variant="success" @click="infoGet(key)"
              ><b-icon-card-text/></b-button>
          </td>
          <td>
              <b-button variant="info" :href="'/admin/challenges/'+challenge.id+'/members/'"><b-icon-people-fill/></b-button>
          </td>
          <td>
            <b-button variant="primary" @click="editGet(key)"
              ><b-icon-vector-pen
            /></b-button>
          </td>
          <td>
            <b-button variant="danger" @click="deleteChallenge(key)"
              ><b-icon-x
            /></b-button>
          </td>
        </tr>
      </tbody>
    </table>
     <pagination  :limit="4"
     v-if="paginationData != null && challenges.length != 0"
        :data="paginationData"
        @pagination-change-page="challengesGet"
        ></pagination>
  </div>
  <spinner v-else />
</template>

<script>
import axios from "axios";
const pagination = () => import("laravel-vue-pagination");
import spinner from "../components/Spinner";
const photos = () => import("../components/PohotosPreview");
const preview = () => import("../components/PreviewViewer");
export default {
  name: "Challenges",
  components: {
    pagination,
    spinner,
    photos,
    preview
  },
  data() {
    return {
      about: "",
      description: "",
      requirments: "",
      moderation: "",
      loaded: false,
      paginationData: null,
      challenges: [],
      intro: "",
      images: [],
      addForm: {
        title: "",
        deadline: null,
        requrements: "",
        description: "",
        moderation: "",
        about: "",
        image: null,
        photos: null,
      },
      editForm:{
          title:"",
          alias:"",
          id: null,
          description:"",
          moderation:"",
          deadline:"",
          preview:null,
          about:"",
          loaded: false,
          requirments:"",
          reload: false,
          examples: null,
          introPreview: [],
          photosPreview: [],
          deletedAvatar:"",
          deletedPhotos:[],
      },
       editFormMask:{
          title:"",
          alias:"",
          description:"",
          moderation:"",
          deadline:"",
          preview:null,
          loaded: false,
          about:"",
          requirments:"",
          id: null,
          reload: false,
          introPreview: [],
          photosPreview: [],
          examples: null,
          deletedAvatar:"",
          deletedPhotos:[],
      }
    };
  },
  methods: {
    reloader(){
      this.editForm.reload = false
      this.$nextTick(() => this.editForm.reload = true)
    },
    rebuild(){
      this.loaded = false;
      this.$nextTick(()=>{
        this.loaded = true
      })
    },

    editSave(e){
      if(this.editForm.title != ""){
        let form = new FormData(this.$refs.editForm)
        let config = { headers: { "Content-Type": "multipart/form-data" } };
        axios.post("/challenge-edit/"+this.editForm.id, form, config)
        .then(
          resp => {
            console.log(resp)
              if(resp.data.success == true){
                let id = resp.data.challenge.id;
                let  i =0;
                for(i;i<this.challenges.length;i++){
                  if(this.challenges[i].id == id){
                    this.challenges[i] = resp.data.challenge
                    this.rebuild();
                  }
                }
              }
          }
        )
      }else{
        e.preventDefault();
        alert("Title filed is required")
      }
    },
    newPreview(event){
      let input = event.target;

      if (input.files.length != 0) {

        var reader = new FileReader();
        // Closure to capture the file information.
        reader.onload = e => {

          this.editForm.introPreview = [e.target.result];
          this.reloader()

        };
        reader.readAsDataURL(input.files[0]);
      }
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
    infoGet(key) {
      this.about = this.challenges[key].about;
      this.description = this.challenges[key].description;
      this.requirments = this.challenges[key].requirments;
      this.moderation = this.challenges[key].moderation;
      this.$refs["info"].show();
    },
    clearEdit(){
        this.editForm = this.editFormMask
        alert("отчщищщено")
    },
    deletePreview(data){
      this.editForm.deletedAvatar = data.photo
      this.editForm.preview = null;
      this.reloader()
    },
    deletePhoto(data){
        this.editForm.deletedPhotos.push(data.photo)
        let  index = null;
        let photos = this.editForm.examples;
        for(let i=0;i<photos.length;i++) if(photos[i] == data.photo) index = i
        this.editForm.reload = false
        let id = this.editForm.id
        let photo = photos[index]
        // axios.post("/challenge-photo/delete/{id}",{
        //   "photo":photo
        // }).then(
        //   resp => {
        //     console.log(resp)
        //   }
        // )
        photos.splice(index,1)
        this.editForm.examples = photos
        this.$nextTick(() => this.editForm.reload = true)

    },
    editGet(key) {
        let info  = this.challenges[key]
        this.editForm.title = info.name
        this.editForm.description = info.description
        this.editForm.moderation = info.moderation
        this.editForm.deadline = info.deadline
        this.editForm.about = info.about
        this.editForm.alias = info.alias
        this.editForm.requirments = info.requirments
        this.editForm.preview = info.challenge_photo
        this.editForm.examples = JSON.parse(info.photos)
        this.editForm.reload = true
        this.editForm.id = info.id
        this.editForm.loaded = true
        this.$refs["editModal"].show();
        console.log(this.editForm)
    },
    deleteChallenge(key) {
      axios.delete("/challenge/" + this.challenges[key].id).then((resp) => {
        this.loaded = false;
        this.challenges.splice(key, 1);
        this.$nextTick(() => {
          this.loaded = true;
        });
      });
    },
    photosGet(key) {
      this.intro = this.challenges[key].challenge_photo;
      this.images = JSON.parse(this.challenges[key].photos);
      this.editForm.alias = this.challenges[key].alias
      this.$refs["Photos"].show();
    },
    challengesGet(page =1) {
      axios.get("/admin-challenges?page="+page).then((resp) => {
        if (resp.status == 200) {
          this.challenges = resp.data.data;
          this.paginationData = resp.data
        }
      });
    },
    addChallenge(e) {
        let toReturn = true;
        let keys = Object.keys(this.addForm)
        for(let i =0; i < keys.length; i++){
            if(this.addForm[keys[i]] == "" || this.addForm[keys[i]] == null)
              toReturn = false
        }
      if(toReturn){
        let form = new FormData(this.$refs.challengeAddForm);
        let config = { headers: { "Content-Type": "multipart/form-data" } };
        axios.post("/challenge-add", form, config).then((resp) => {
          if (resp.status == 200) {
            this.challenges.push(resp.data.challenge);

          }
        });
      }else{
        alert("All data must be filled.")
        e.preventDefault()
        return false;
      }
      
    },
  },
  mounted() {
    this.challengesGet();
    this.loaded = true;
  },
};
</script>

<style scoped>
</style>

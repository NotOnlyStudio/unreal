<template>
    <div v-if="renderComponent" @keydown.esc="hidePhotoEsc">

        <b-button variant="primary" v-b-modal.modal-1><b-icon-plus-circle class="mr-2"/>  new</b-button>
        <table class="table my-3 table-striped">
            <thead class="thead-dark">
                <th>ID</th>
                <th>Order</th>
                <th>Title</th>
                <th>Parent</th>
                <th>Photo</th>
                <th>Edit</th>
                <th>Delete</th>
            </thead>
            <tbody>
                <tr v-for="(cat,key) in categories" :key="key">
                    <td>{{cat.id}}</td>
                    <td>{{cat.order}}</td>
                    <td>{{cat.name}}</td>
                    <td>{{cat.parent == null ? "No" : cat.parent }}</td>
                    <td><b-button v-if="cat.image != 'null'" @click="getPhoto(key)" variant="primary"><b-icon-image-fill/></b-button> <p v-else>No</p></td>
                    <td><b-button variant="info" @click="editGet(key)"  v-b-modal.modal-editForm><b-icon-vector-pen/></b-button></td>
                    <td><b-button @click="deleteCat(key)" variant="danger"><b-icon-x/></b-button></td>
                </tr>
            </tbody>
        </table>
        <b-modal ref="editForm" id="editForm" @ok="submitEdit" title="Edit category">
            <b-form ref="formEdit" enctype="multipart/form-data" >
                <b-form-group
                    label="Title"
                    label-for="title"
                >
                    <b-form-input name="name" id="title" v-model="eform.title" placeholder="Title"></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Order"
                    label-for="order"
                >
                    <b-form-input name="order" id="order" v-model="eform.order" placeholder="Order"></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Parent"
                    label-for="parent"
                >
                    <select class="form-control" name="parent" v-model="eform.parent" id="parent">
                        <option value="none" selected>None</option>
                        <option v-for="cat in categories"  :value="cat.id" :selected="cat.id == eform.id ? 'selected' : 'none'">{{cat.name}}</option>
                    </select>
                </b-form-group>
                <input type="hidden" v-model="eform.photoName" name="photoName">
                <b-form-group
                    label="Photo"
                    label-for="photo"
                >

                    <b-form-file  v-model="eform.photo" id="photo" name="photo"></b-form-file>
                </b-form-group>
                <input type="hidden" name="_method" value="PUT">
            </b-form>

        </b-modal>
        <b-modal @ok="addNew" id="modal-1" title="Add new category">
            <b-form enctype="multipart/form-data" ref="categoryUpload">
                <b-form-group
                    id="input-group-1"
                    label="Title:"
                    label-for="title"
                >
                    <b-form-input
                        id="title"
                        v-model="form.name"
                        type="text"
                        name="name"
                        placeholder="Title"
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    id="parent"
                    label="Parent category:"
                    label-for="parent"
                >
                    <select name="parent" id="parent" class="form-control">
                        <option value="none" selected disabled>Not selected</option>
                        <option v-for="cat in categories" :value="cat.id">{{cat.name}}</option>
                    </select>
                </b-form-group>
                <b-form-group
                    id="photo-label"
                    label="Choose category photo:"
                    label-for="photo"
                >
                     <b-form-file id="photo" name="photo"></b-form-file>
                </b-form-group>
            </b-form>
        </b-modal>
        <b-alert
            v-show="alerting"
            dismissible
            variant="warning"
        >
            <p>{{errorMsg}}</p>
        </b-alert>
        <div v-show="showPhoto"  @keydown.esc="hidePhotoEsc" @click="hidePhoto" class="img__wrapper">
            <div class="img__inner">
                <button class="close">
                    <span>&times;</span>
                </button>
                <img :src="photoSrc" alt="Big photo"></div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    export default {
        name: "Categories",
        data(){
            return{
                categories: ["None"],
                form:{
                    name:"",
                    parent:"",
                },
                eform:{
                     id:"",
                   title:"",
                   parent: "none",
                   order: 0,
                    file: null,
                    photoName:"",
                    key: null,
                },
                renderComponent: true,
                edit: false,
                alerting: false,
                errorMsg: "",
                showPhoto: false,
                photoSrc: "",
            }
        },
        components:{
        },
        methods:{
            editFormSet(){

            },
            editGet(key){
                let info = this.categories[key];
                this.eform.parent = info.parent;
                this.eform.title = info.name;
                this.eform.order = info.order;
                this.eform.id = this.categories[key].id
                this.eform.photoName = this.categories[key].image;
                this.eform.key = key;
                this.$refs['editForm'].show();
            },
            submitEdit(){
                let config = { headers: { 'Content-Type': 'multipart/form-data' } }
                let formData = new FormData(this.$refs.formEdit);
                console.log(formData)
                if(this.eform.name != ""){
                    axios.post('/category/'+this.eform.id, formData, config).then( resp => {
                            let key = this.eform.key;
                            this.renderComponent = false;
                            this.categories[key]= resp.data.data;
                            this.$nextTick(() => {
                            this.renderComponent = true;
                        });

                        }
                    )
                }
            },
            hidePhoto(e){
                if(e.target.tagName != "IMG"){
                    this.showPhoto = false;
                }
            },
            hidePhotoEsc(){
                this.showPhoto = false;
            },
            getPhoto(key){
                let src = this.categories[key].image
                this.photoSrc = "/storage/app/public/categories/"+src.replace(/"/g, '');
                this.showPhoto = true;
            },
            deleteCat(key){
                let catId = this.categories[key].id;
                axios.delete("/category/"+catId).then(
                    resp => {
                        if(resp.data.success == true){
                            this.categories.splice(key,1);
                        }
                        console.log(resp)
                    }
                )
            },
            addNew(){
                var formData = new FormData(this.$refs.categoryUpload)
                let config = { headers: { 'Content-Type': 'multipart/form-data' } }

                if(this.form.name != ""){
                    axios.post('/category', formData, config).then( resp => {
                            if(resp.data.success){
                                this.categories.push(resp.data.item)
                            }
                        }
                    )
                }else{
                    this.errorMsg = "Please fill in all details.";
                    this.alerting = true;
                    setTimeout(function(){
                        this.alerting = false;
                    },3000)
                }
            }
        },
        mounted(){
            axios.get('/category?order=asc')
            .then( resp => {
                console.log(resp)
               if(resp) this.categories = resp.data;
            })
        }
    }
</script>

<style scoped>
    .close span{
        font-size: 24px;
        color: white;
    }
    .close{
        background: none;
        position: absolute;
        top: 20px;
        user-select: none;
        right: 20px;
        z-index: 15;
        border: none;
        transition: .3s linear;
    }
    img{
        max-width: 90%;
        max-height: 90%;
    }
    .close:hover{
        opacity: .5;
    }
    .img__wrapper{
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        position: fixed;
        z-index: 15;
    }
    .img__inner{
        position: relative;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
